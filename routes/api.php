<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LicenseController;

Route::middleware(['throttle:60,1', 'validate.api.secret', 'rate.limit.api'])->group(function () {
    // License checking endpoints
    Route::post('/check-license', [LicenseController::class, 'checkLicense']);
    Route::get('/license/{license_key}', [LicenseController::class, 'getLicenseInfo']);
});

// Health check (no auth required)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String()
    ]);
});
