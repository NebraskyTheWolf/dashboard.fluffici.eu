<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
<<<<<<< HEAD
    public function boot(): void {
=======
    public function boot(): void
    {
>>>>>>> 10223f9b78d8fa2d63823686a7307cb95204bfe1
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
