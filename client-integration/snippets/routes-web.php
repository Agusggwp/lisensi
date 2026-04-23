<?php

use Illuminate\Support\Facades\Route;

Route::middleware('client.license')->group(function (): void {
    
    // Tambahkan route website utama Anda di sini
});

Route::view('/license-expired', 'license.expired')->name('license.expired');
Route::view('/maintenance-license', 'license.maintenance')->name('license.maintenance');
