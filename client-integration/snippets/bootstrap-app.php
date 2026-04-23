<?php

/*
Tambahkan potongan berikut di bootstrap/app.php project client,
di dalam chain Application::configure(...):

->withMiddleware(function (\Illuminate\Foundation\Configuration\Middleware $middleware): void {
    $middleware->alias([
        'client.license' => \App\Http\Middleware\EnsureValidLicense::class,
    ]);
})
*/
