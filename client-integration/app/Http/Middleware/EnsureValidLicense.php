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
        $licenseKey = env('CLIENT_LICENSE_KEY');

        if (! $licenseKey) {
            return response()->view('license.expired', [
                'message' => 'CLIENT_LICENSE_KEY belum diatur pada .env',
            ], 423);
        }

        $domain = strtolower($request->getHost());
        $ipAddress = $request->ip();
        $timestamp = time();
        $secret = (string) env('LICENSE_SERVER_HMAC_SECRET');

        $signatureBase = implode('|', [$licenseKey, $domain, (string) $ipAddress, (string) $timestamp]);
        $signature = hash_hmac('sha256', $signatureBase, $secret);

        $cacheKey = 'license-check:' . sha1($domain . '|' . $licenseKey);
        $cacheSeconds = (int) env('CLIENT_LICENSE_CACHE_SECONDS', 600);
        $isIpLockEnabled = filter_var((string) env('CLIENT_LICENSE_IP_LOCK', 'false'), FILTER_VALIDATE_BOOL);

        try {
            $response = Http::timeout(8)
                ->acceptJson()
                ->post(rtrim((string) env('CLIENT_LICENSE_SERVER_URL'), '/') . env('CLIENT_LICENSE_CHECK_PATH', '/api/v1/licenses/validate'), [
                    'license_key' => $licenseKey,
                    'domain' => $domain,
                    'ip_address' => $isIpLockEnabled ? $ipAddress : null,
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

            Cache::put($cacheKey, $json, now()->addSeconds($cacheSeconds));
        } catch (ConnectionException) {
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
}
