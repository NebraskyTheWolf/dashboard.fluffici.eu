<?php

namespace App\Http\Middleware;

use App\Models\UserApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication
{
    /**
     * Handle the request.
     *
     * This method handles the incoming request by performing the necessary checks for authentication
     * and permission access. If the authentication fails or the user does not have the required permission,
     * an appropriate response is returned. Otherwise, the request is processed and passed on to the next
     * middleware or route handler.
     *
     * @param Request $request The incoming request object.
     * @param \Closure $next The closure representing the next middleware or route handler.
     * @param string $permission The required permission for the operation.
     * @return \Illuminate\Http\Response The response object.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
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
                'message' => 'Your account is terminated.'
            ]);
        }

        // Permission blocked
        if (!$token->getUser()->hasAccess($permission)) {
            return response()->json([
                'status' => false,
                'error' => 'PERMISSION_DENIED',
                'message' => 'You don\'t have the permission to perform this operation.'
            ]);
        }

        $request->merge(['user_id' => $token->user_id]);
        $request->merge(['username' => $token->getUser()->name]);

        return $next($request);
    }
}
