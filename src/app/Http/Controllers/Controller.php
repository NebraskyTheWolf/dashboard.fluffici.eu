<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
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
}
