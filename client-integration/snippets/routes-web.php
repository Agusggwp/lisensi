<?php

use Illuminate\Support\Facades\Route;

// Route website utama Anda tetap normal.
// Proteksi lisensi sudah global via bootstrap/app.php (web middleware append).

Route::view('/license-expired', 'license.expired')->name('license.expired');
Route::view('/maintenance-license', 'license.maintenance')->name('license.maintenance');
