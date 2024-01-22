<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (Request $request) {
    return [
        'message' => 'OwO'
    ];
});

Route::get('/user/notifications/{id}', function (Request $request) {
    if ($request->has('id')) {

        $notifications = \Orchid\Platform\Models\User::where('id', $request->input('id'))


    } else {
        return response()->json([
            'status' => false,
            'error' => 'The user id is missing.'
        ]);
    }
});
