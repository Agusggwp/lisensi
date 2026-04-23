<?php

use App\Http\Controllers\Api\LicenseValidationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/licenses/validate', LicenseValidationController::class)->name('api.licenses.validate');
});
