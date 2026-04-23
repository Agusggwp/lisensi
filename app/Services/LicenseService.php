<?php

namespace App\Services;

use App\Models\Client;
use App\Models\LicenseCheck;
use Illuminate\Support\Str;
use Exception;

class LicenseService
{
    /**
     * Generate unique license key
     */
    public function generateLicenseKey(): string
    {
        do {
            $key = 'LIC-' . strtoupper(Str::random(12)) . '-' . now()->format('Ym');
        } while (Client::where('license_key', $key)->exists());

        return $key;
    }

    /**
     * Check license validity
     * 
     * @param string $licenseKey
     * @param string $domain
     * @param string|null $ipAddress
     * @param string|null $userAgent
     * @return array
     */
    public function checkLicense(
        string $licenseKey,
        string $domain,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): array
    {
        $response = [
            'status' => 'invalid',
            'message' => 'Lisensi tidak ditemukan.',
            'valid' => false
        ];

        try {
            // Find client by license key
            $client = Client::where('license_key', $licenseKey)->first();

            if (!$client) {
                LicenseCheck::create([
                    'client_id' => null,
                    'license_key' => $licenseKey,
                    'domain' => $domain,
                    'status' => 'invalid',
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);

                return $response;
            }

            // Check domain validation
            if ($client->domain !== $domain) {
                $response['message'] = 'Domain tidak cocok dengan lisensi yang terdaftar.';

                LicenseCheck::create([
                    'client_id' => $client->id,
                    'license_key' => $licenseKey,
                    'domain' => $domain,
                    'status' => 'invalid',
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);

                return $response;
            }

            // Check if suspended
            if ($client->isSuspended()) {
                $response = [
                    'status' => 'suspended',
                    'message' => 'Lisensi telah dibekukan. Silakan hubungi administrator.',
                    'valid' => false
                ];

                LicenseCheck::create([
                    'client_id' => $client->id,
                    'license_key' => $licenseKey,
                    'domain' => $domain,
                    'status' => 'suspended',
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);

                return $response;
            }

            // Check if expired
            if ($client->isExpired()) {
                $response = [
                    'status' => 'expired',
                    'message' => 'Lisensi sudah habis, silakan lakukan pembayaran untuk memperpanjang.',
                    'client_name' => $client->name,
                    'client_email' => $client->email,
                    'expired_at' => $client->expired_at->format('Y-m-d'),
                    'valid' => false
                ];

                $client->update(['status' => 'expired']);

                LicenseCheck::create([
                    'client_id' => $client->id,
                    'license_key' => $licenseKey,
                    'domain' => $domain,
                    'status' => 'expired',
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);

                return $response;
            }

            // License is valid and active
            $response = [
                'status' => 'active',
                'message' => 'Lisensi valid dan masih aktif.',
                'client_name' => $client->name,
                'client_email' => $client->email,
                'expired_at' => $client->expired_at->format('Y-m-d'),
                'days_remaining' => $client->daysUntilExpiry(),
                'valid' => true
            ];

            LicenseCheck::create([
                'client_id' => $client->id,
                'license_key' => $licenseKey,
                'domain' => $domain,
                'status' => 'active',
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);

            return $response;

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengecek lisensi.',
                'valid' => false
            ];
        }
    }

    /**
     * Get license info
     */
    public function getLicenseInfo(string $licenseKey): ?array
    {
        $client = Client::where('license_key', $licenseKey)->first();

        if (!$client) {
            return null;
        }

        return [
            'name' => $client->name,
            'email' => $client->email,
            'domain' => $client->domain,
            'status' => $client->status,
            'expired_at' => $client->expired_at->format('Y-m-d'),
            'days_remaining' => $client->daysUntilExpiry(),
            'created_at' => $client->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Auto expire licenses
     */
    public function autoExpireLicenses(): void
    {
        Client::active()
            ->where('expired_at', '<=', now())
            ->update(['status' => 'expired']);
    }
}
