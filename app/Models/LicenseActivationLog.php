<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseActivationLog extends Model
{
    protected $fillable = [
        'license_id',
        'domain',
        'ip_address',
        'user_agent',
        'is_valid',
        'reason',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'is_valid' => 'boolean',
            'payload' => 'array',
        ];
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
