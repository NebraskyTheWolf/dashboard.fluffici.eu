<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orchid\Platform\Models\User;

Route::post('/api/login', [ApiController::class, 'index']);
Route::post('/api/login/otp-challenge', [ApiController::class, 'validateOtp']);

Route::middleware('auth.api:api.calendar.add')->get('/user/@me',  function (Request $request) {
    $user = User::where('id', $request->input('user_id'))->first();
    if ($user->isTerminated()) {
        return response()->json([
            'status' => false,
            'error' => "ACCOUNT_TERMINATED",
            'message' => 'Your account is terminated.'
        ]);
    } else {
        return response()->json([
            'status' => true,
            'data' => [
                'username' => $user->name,
                'email' => $user->email,
                'permissions' => $user->permissions
            ]
        ]);
    }
});
