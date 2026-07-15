<?php

putenv('APP_NAME=Nano Spark LMS');
putenv('APP_ENV=production');
putenv('APP_KEY=base64:5mqffIXKfmg6eSPA0URbWGK9Yoq3AXzAcv02HUQ+K48=');
putenv('APP_DEBUG=false');
putenv('APP_URL=' . ($_SERVER['VERCEL_URL'] ?? 'https://nanosparklms.vercel.app'));
putenv('LOG_CHANNEL=stderr');
putenv('LOG_LEVEL=debug');
putenv('DB_CONNECTION=pgsql');
putenv('DB_HOST=' . ($_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'db.cypdaqufwyiilrwquatm.supabase.co'));
putenv('DB_PORT=' . ($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '5432'));
putenv('DB_DATABASE=' . ($_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: 'postgres'));
putenv('DB_USERNAME=' . ($_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: 'postgres'));
putenv('DB_PASSWORD=' . ($_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: ''));
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_DRIVER=array');
putenv('FILESYSTEM_DISK=local');
putenv('QUEUE_CONNECTION=sync');
putenv('MAIL_MAILER=log');

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
