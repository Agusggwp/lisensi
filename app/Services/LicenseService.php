<?php

namespace App\Services;

use App\Models\License;
use App\Models\LicenseActivationLog;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class LicenseService
{
    public function generateLicenseKey(): string
    {
        return 'LIC-' . strtoupper(Str::random(8)) . '-' . strtoupper(Str::random(8));
    }

    public function generateEncryptedToken(License $license): string
    {
        $payload = [
            'license_key' => $license->license_key,
            'domain' => $license->domain,
            'expires_at' => optional($license->expires_at)?->toIso8601String(),
            'status' => $license->status,
        ];

        return Crypt::encryptString(json_encode($payload, JSON_THROW_ON_ERROR));
    }

    public function validate(string $licenseKey, string $domain, ?string $ipAddress = null): array
    {
        $license = License::query()->where('license_key', $licenseKey)->first();

        if (! $license) {
            return $this->response(false, 'License key tidak ditemukan', null);
        }

        if ($license->status === License::STATUS_SUSPENDED) {
            return $this->logAndRespond($license, false, 'License suspended', $domain, $ipAddress);
        }

        if ($license->isExpired() || $license->status === License::STATUS_EXPIRED) {
            return $this->logAndRespond($license, false, 'License expired', $domain, $ipAddress);
        }

        if ($license->is_domain_locked && strcasecmp($license->domain, $domain) !== 0) {
            return $this->logAndRespond($license, false, 'Domain mismatch', $domain, $ipAddress);
        }

        if ($license->is_ip_locked && $license->ip_lock && $ipAddress && $license->ip_lock !== $ipAddress) {
            return $this->logAndRespond($license, false, 'IP mismatch', $domain, $ipAddress);
        }

        $license->forceFill([
            'last_validated_at' => now(),
        ])->save();

        return $this->logAndRespond($license, true, 'License valid', $domain, $ipAddress);
    }

    public function syncExpiredStatuses(): void
    {
        License::query()
            ->where('status', License::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update([
                'status' => License::STATUS_EXPIRED,
            ]);
    }

    private function response(bool $valid, string $message, ?License $license): array
    {
        return [
            'valid' => $valid,
            'message' => $message,
            'status' => $license?->status,
            'expires_at' => optional($license?->expires_at)?->toIso8601String(),
            'license' => $license ? [
                'id' => $license->id,
                'name' => $license->name,
                'domain' => $license->domain,
                'encrypted_token' => $license->encrypted_token,
            ] : null,
        ];
    }

    private function logAndRespond(License $license, bool $isValid, string $reason, string $domain, ?string $ipAddress): array
    {
        LicenseActivationLog::query()->create([
            'license_id' => $license->id,
            'domain' => $domain,
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent(),
            'is_valid' => $isValid,
            'reason' => $reason,
            'payload' => [
                'license_key' => $license->license_key,
                'status' => $license->status,
            ],
        ]);

        return $this->response($isValid, $reason, $license);
    }
}
