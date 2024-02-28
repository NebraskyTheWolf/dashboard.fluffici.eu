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

    public const string HOME = '/main';


    /**
     * The boot method sets up the application before it is ready to handle requests.
     *
     * This method is called once when the application boots up. It is typically used for initialization and
     * registration of various components, such as routes, service providers, configurations, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('auth')
                ->group(base_path('routes/auth.php'));

            Route::middleware('device')
                ->group(base_path('routes/device.php'));

            Route::middleware('integrations')
                ->group(base_path('routes/integrations.php'));

            Route::middleware('backend')
                ->group(base_path('routes/backend.php'));
        });
    }
}
