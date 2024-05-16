<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Platform\Models\User;
use Symfony\Component\HttpFoundation\Response;

class LanguageDetector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::guest() && $request->user()->language !== null) {
            app()->setLocale($request->user()->language);
            session()->put('locale', $request->user()->language);
            $request->setLocale($request->user()->language);
            return $next($request);
        }

        $request->setLocale('cs');
        session()->put('locale', 'cs');
        return $next($request);
    }
}
