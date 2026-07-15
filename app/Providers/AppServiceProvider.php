<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CheckSchoolActive;
use App\Http\Middleware\DarkModeMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::aliasMiddleware('role', RoleMiddleware::class);
        Route::aliasMiddleware('school.active', CheckSchoolActive::class);
        Route::aliasMiddleware('darkmode', DarkModeMiddleware::class);
    }
}
