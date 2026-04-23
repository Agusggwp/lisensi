<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseValidationController extends Controller
{
    public function __construct(private readonly LicenseService $licenseService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $secret = (string) config('services.license_server.hmac_secret');

        $payload = $request->validate([
            'license_key' => ['required', 'string'],
            'domain' => ['required', 'string'],
            'ip_address' => ['nullable', 'ip'],
            'timestamp' => ['required', 'integer'],
            'signature' => ['required', 'string'],
        ]);

        if (abs(time() - (int) $payload['timestamp']) > 300) {
            return response()->json([
                'valid' => false,
                'message' => 'Request expired',
            ], 401);
        }

        $signedBody = implode('|', [
            $payload['license_key'],
            strtolower($payload['domain']),
            (string) ($payload['ip_address'] ?? ''),
            (string) $payload['timestamp'],
        ]);

        $computed = hash_hmac('sha256', $signedBody, $secret);

        if (! hash_equals($computed, $payload['signature'])) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid signature',
            ], 401);
        }

        $result = $this->licenseService->validate(
            $payload['license_key'],
            strtolower($payload['domain']),
            $payload['ip_address'] ?? $request->ip()
        );

        return response()->json($result, $result['valid'] ? 200 : 423);
    }
}
