<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
<<<<<<< HEAD
    public function register(): void { }
=======
    public function register(): void {
        if (!class_exists('Requester'))
            require base_path('app/Http/Requester.class.php');
        $api = new \Requester(env('HOSTNAME', 'http://localhost/api'), env('BACKEND_BEARER_TOKEN', 'default'));
        $this->app->instance('\Requester', $api);
    }
>>>>>>> 10223f9b78d8fa2d63823686a7307cb95204bfe1

    /**
     * Bootstrap any application services.
     */
<<<<<<< HEAD
    public function boot(): void { }
=======
    public function boot(): void
    {
        //
    }
>>>>>>> 10223f9b78d8fa2d63823686a7307cb95204bfe1
}
