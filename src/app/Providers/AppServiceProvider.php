<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void { }

    /**
     * Boot the application.
     *
     * This method is called after all service providers have been registered
     * and the application is ready to handle incoming requests.
     *
     * @return void
     */
    public function boot(): void {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
