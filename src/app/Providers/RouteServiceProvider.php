<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
<<<<<<< HEAD
    public const string HOME = '/main';
=======
    public const HOME = '/main';
>>>>>>> 10223f9b78d8fa2d63823686a7307cb95204bfe1

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
<<<<<<< HEAD
    public function boot(): void {
        $this->routes(function () {
=======
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

>>>>>>> 10223f9b78d8fa2d63823686a7307cb95204bfe1
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
