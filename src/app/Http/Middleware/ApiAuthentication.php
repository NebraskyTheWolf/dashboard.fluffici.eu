<?php

namespace App\Http\Middleware;

use App\Models\UserApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication extends Middleware
{
    /**
     * This method checks and handles the authentication token in the request.
     *
     * @param Request $request The HTTP request object.
     * @param Closure $next The next middleware or controller closure.
     * @return Response The HTTP response object.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->bearerToken();
        if ($header == null) {
            return response()->json([
                'status' => false,
                'error' => 'AUTHENTICATION_TOKEN',
                'message' => 'No bearer token found.'
            ]);
        }

        $token = UserApiToken::where('token', $header);
        if (!$token->exists()) {
            return response()->json([
                'status' => false,
                'error' => 'AUTHENTICATION_TOKEN',
                'message' => 'Invalid bearer token.'
            ]);
        }

        $token = $token->first();
        if ($token->getUser()->isTerminated()) {
            return response()->json([
                'status' => false,
                'error' => 'AUTHENTICATION_TOKEN',
                'message' => 'User is terminated.'
            ]);
        }

        $request->merge(['user_id' => $token->user_id]);

        return $next($request);
    }
}
