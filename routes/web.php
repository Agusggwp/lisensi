<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LicenseController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('client.home'));

Route::middleware('client.license')->group(function (): void {
    Route::view('/client', 'client.home')->name('client.home');
});

Route::view('/license-expired', 'license.expired')->name('license.expired');
Route::view('/maintenance-license', 'license.maintenance')->name('license.maintenance');
Route::get('/license/verify/{licenseKey}', [LicenseController::class, 'verify'])->name('license.verify');

Route::prefix('admin')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware(['auth', 'admin.only'])->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');

        Route::resource('/clients', ClientController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.clients');
        Route::resource('/licenses', LicenseController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.licenses');
        Route::post('/licenses/{license}/toggle-status', [LicenseController::class, 'toggleStatus'])->name('admin.licenses.toggle-status');
        Route::post('/licenses/{license}/extend', [LicenseController::class, 'extend'])->name('admin.licenses.extend');
    });
});
