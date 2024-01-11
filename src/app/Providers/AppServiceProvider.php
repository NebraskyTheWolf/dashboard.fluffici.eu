<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {
        if (!class_exists('Requester'))
            require base_path('app/Http/Requester.class.php');
        $api = new \Requester(env('HOSTNAME', 'http://localhost/api'), env('BACKEND_BEARER_TOKEN', 'default'));
        $this->app->instance('\Requester', $api);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
