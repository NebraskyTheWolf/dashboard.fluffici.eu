<?php

namespace App\Http\Controllers;

use App\Models\UserApiToken;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Check if the user has the specified permission.
     *
     * @param string $permission The permission to check.
     *
     * @return void
     */
    protected function checkPermission(string $permission): void
    {
        $this->middleware(static function ($request, $next) use ($permission) {
            if (Auth::user()->hasAccess($permission)) {
                return $next($request);
            }
            abort(403);
        });

        abort_if(Auth::user() !== null && ! Auth::user()->hasAccess($permission), 403);
    }

    /**
     * Retrieves data from the specified slug using the GET method.
     *
     * @param string $slug The slug used to identify the resource.
     * @return mixed The data retrieved from the specified slug.
     */
    public function get($slug) {
        return resolve('\Requester')->get($slug);
    }

    /**
     * Checks the validity of the authentication token provided in the request.
     *
     * @param \Illuminate\Http\Request $request The request object containing the bearer token.
     * @return \Illuminate\Http\JsonResponse|bool Returns a JsonResponse if token is invalid or false if no bearer token is found.
     */
    public function checkToken(Request $request): \Illuminate\Http\JsonResponse|bool
    {
        $header = $request->bearerToken();
        if ($header == null) {
            \response()->json([
                'status' => false,
                'error' => 'AUTHENTICATION_TOKEN',
                'message' => 'No bearer token found.'
            ]);
        } else {
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
            $request->merge(['username' => $token->getUser()->name]);

            return true;
        }

        return false;
    }
}
