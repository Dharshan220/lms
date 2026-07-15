<?php

putenv('SESSION_DRIVER=cookie');
putenv('CACHE_DRIVER=array');
putenv('LOG_CHANNEL=stderr');

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$storagePath = sys_get_temp_dir() . '/laravel_storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0775, true);
    mkdir($storagePath . '/framework/cache/data', 0775, true);
    mkdir($storagePath . '/framework/sessions', 0775, true);
    mkdir($storagePath . '/framework/views', 0775, true);
    mkdir($storagePath . '/logs', 0775, true);
}

$app->useStoragePath($storagePath);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
