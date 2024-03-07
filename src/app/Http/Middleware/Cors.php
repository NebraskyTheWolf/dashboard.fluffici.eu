<?php

namespace app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $request->header([
            '' => ''
        ]);

        return $next($request);
    }
}
