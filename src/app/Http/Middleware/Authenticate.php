<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
<<<<<<< HEAD
    protected function redirectTo(Request $request): ?string {
        return $request->expectsJson() ? null : route('/login');
=======
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('/admin/login');
>>>>>>> 10223f9b78d8fa2d63823686a7307cb95204bfe1
    }
}
