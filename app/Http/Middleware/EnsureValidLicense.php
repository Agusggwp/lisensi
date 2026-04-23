<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidLicense
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        $licenseKey = env('CLIENT_LICENSE_KEY');

        if (! $licenseKey) {
            return response()->view('license.expired', [
                'message' => 'LICENSE_KEY belum diatur pada .env client.',
            ], 423);
        }

        $domain = strtolower($request->getHost());
        $ipAddress = $request->ip();
        $timestamp = time();
        $secret = (string) config('services.license_server.hmac_secret');
        $isIpLockEnabled = filter_var((string) env('CLIENT_LICENSE_IP_LOCK', 'false'), FILTER_VALIDATE_BOOL);
        $signatureIpPart = $isIpLockEnabled ? (string) $ipAddress : '';

        $signatureBase = implode('|', [$licenseKey, $domain, $signatureIpPart, (string) $timestamp]);
        $signature = hash_hmac('sha256', $signatureBase, $secret);

        $cacheKey = 'license-check:' . sha1($domain . '|' . $licenseKey);
        $cacheSeconds = (int) env('CLIENT_LICENSE_CACHE_SECONDS', 0);
        $allowStaleCache = filter_var((string) env('CLIENT_LICENSE_ALLOW_STALE_CACHE', 'false'), FILTER_VALIDATE_BOOL);

        try {
            $response = Http::timeout(8)
                ->acceptJson()
                ->post(rtrim((string) env('CLIENT_LICENSE_SERVER_URL'), '/') . env('CLIENT_LICENSE_CHECK_PATH', '/api/v1/licenses/validate'), [
                    'license_key' => $licenseKey,
                    'domain' => $domain,
                    'ip_address' => $isIpLockEnabled ? $ipAddress : '',
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ]);

            if (! $response->ok()) {
                return $this->blockByMode($request, $response->json('message') ?? 'License invalid.');
            }

            $json = $response->json();

            if (! ($json['valid'] ?? false)) {
                return $this->blockByMode($request, (string) ($json['message'] ?? 'License invalid.'));
            }

            if ($cacheSeconds > 0) {
                Cache::put($cacheKey, $json, now()->addSeconds($cacheSeconds));
            }
        } catch (ConnectionException) {
            if (! $allowStaleCache) {
                return $this->blockByMode($request, 'Tidak dapat menghubungi license server.');
            }

            $cached = Cache::get($cacheKey);

            if (! $cached || ! ($cached['valid'] ?? false)) {
                return $this->blockByMode($request, 'Tidak dapat menghubungi license server.');
            }
        }

        return $next($request);
    }

    private function blockByMode(Request $request, string $message): Response
    {
        $mode = env('CLIENT_LICENSE_FAIL_MODE', 'expired');

        if ($request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'status' => 'license_blocked',
            ], 423);
        }

        if ($mode === 'maintenance') {
            return response()->view('license.maintenance', ['message' => $message], 503);
        }

        return response()->view('license.expired', ['message' => $message], 423);
    }

    private function shouldBypass(Request $request): bool
    {
        if ($request->is('license-expired') || $request->is('maintenance-license')) {
            return true;
        }

        if ($request->is('up') || $request->is('api/health')) {
            return true;
        }

        return false;
    }
}
