<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('auth.login');

    // Payment & Renewal (Public)
    Route::get('/renewal', [PaymentController::class, 'showRenewal'])->name('payment.renewal');
    Route::post('/payment/create-transaction', [PaymentController::class, 'createTransaction'])->name('payment.create-transaction');
    Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
    Route::get('/payment/status/{orderId}', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Clients Management
    Route::prefix('clients')->name('admin.clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');

        // Actions
        Route::post('/{client}/suspend', [ClientController::class, 'suspend'])->name('suspend');
        Route::post('/{client}/activate', [ClientController::class, 'activate'])->name('activate');
        Route::post('/{client}/regenerate-key', [ClientController::class, 'regenerateLicenseKey'])->name('regenerate-key');
        Route::post('/{client}/extend', [ClientController::class, 'extendLicense'])->name('extend');
    });

    // Logout
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
});
