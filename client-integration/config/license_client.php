<?php

return [
    'server_url' => env('CLIENT_LICENSE_SERVER_URL', 'http://127.0.0.1:8000'),
    'check_path' => env('CLIENT_LICENSE_CHECK_PATH', '/api/v1/licenses/validate'),
    'license_key' => env('CLIENT_LICENSE_KEY'),
    'hmac_secret' => env('LICENSE_SERVER_HMAC_SECRET'),
    'fail_mode' => env('CLIENT_LICENSE_FAIL_MODE', 'expired'),
    'ip_lock' => env('CLIENT_LICENSE_IP_LOCK', false),
    'cache_seconds' => env('CLIENT_LICENSE_CACHE_SECONDS', 600),
];
