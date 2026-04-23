<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'domain',
        'license_key',
        'expired_at',
        'status',
        'price',
        'notes',
        'midtrans_order_id'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = ['expired_at'];

    /**
     * Get all license checks for this client
     */
    public function licenseChecks(): HasMany
    {
        return $this->hasMany(LicenseCheck::class);
    }

    /**
     * Get all reminders for this client
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(LicenseReminder::class);
    }

    /**
     * Get all API logs for this client
     */
    public function apiLogs(): HasMany
    {
        return $this->hasMany(ApiLog::class);
    }

    /**
     * Check if license is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && now()->isBefore($this->expired_at);
    }

    /**
     * Check if license is expired
     */
    public function isExpired(): bool
    {
        return now()->isAfter($this->expired_at);
    }

    /**
     * Check if license is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Get days until expiry
     */
    public function daysUntilExpiry(): int
    {
        return now()->diffInDays($this->expired_at, false);
    }

    /**
     * Suspend the license
     */
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    /**
     * Activate the license
     */
    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Check if expired and update status
     */
    public function checkAndUpdateStatus(): void
    {
        if ($this->isExpired() && $this->status !== 'suspended') {
            $this->update(['status' => 'expired']);
        }
    }

    /**
     * Extend license expiry date
     */
    public function extendLicense(int $days = 30): void
    {
        $this->update([
            'expired_at' => $this->expired_at->addDays($days),
            'status' => 'active'
        ]);
    }

    /**
     * Get license key masked
     */
    protected function licenseKeyMasked(): Attribute
    {
        return Attribute::make(
            get: fn () => substr($this->license_key, 0, 8) . '***' . substr($this->license_key, -4),
        );
    }

    /**
     * Scope: Active clients
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('expired_at', '>', now());
    }

    /**
     * Scope: Expired clients
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Scope: Suspended clients
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}
