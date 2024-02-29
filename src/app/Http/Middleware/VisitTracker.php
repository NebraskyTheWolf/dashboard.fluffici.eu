<?php

namespace app\Http\Middleware;

use App\Models\VisitsStatistics;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VisitTracker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fuck google tracking :)
        // We use our own system for statistics

        $visit = new VisitsStatistics();
        $visit->application_slug = 'dashboard';
        $visit->ip = $request->ip();

        if ($request->hasHeader('HTTP_CF_IPCOUNTRY')) {
            $visit->country = $request->header('HTTP_CF_IPCOUNTRY');
        } else {
            $response = Http::get("http://ip-api.com/json/{$request->ip()}");

            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['country'])) {
                    $visit->country = $data['country'];
                } else {
                    $visit->country = 'unknown';
                }
            }
        }

        $visit->path = $request->path();
        $visit->save();

        return $next($request);
    }
}
