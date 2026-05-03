<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keep your existing Tailwind pagination
        Paginator::useTailwind();

        // Force HTTPS for Render deployments so login forms work securely
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
