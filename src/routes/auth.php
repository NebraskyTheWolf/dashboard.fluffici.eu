<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/api/login', [ApiController::class, 'index']);
Route::post('/api/login/otp-challenge', [ApiController::class, 'validateOtp']);

// Authentication Routes...
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::middleware('throttle:60,1')
    ->post('login', [LoginController::class, 'login'])
    ->name('login.auth');

Route::middleware('throttle:60,1')
    ->post('otp', [LoginController::class, 'otp'])
    ->name('login.otp');

Route::get('otp-challenge', [LoginController::class, 'challenge'])
    ->name('login.challenge');

Route::middleware('throttle:60,1')
    ->post('recovery', [LoginController::class, 'recovery'])
    ->name('login.recovery');

Route::get('password-recovery/{token}', [LoginController::class, 'password'])
    ->name('api.login.recovery');

Route::get('password-new', [LoginController::class, 'password'])
    ->name('login.new-password');

Route::get('lock', [LoginController::class, 'resetCookieLockMe'])->name('login.lock');
Route::get('switch-logout', [LoginController::class, 'switchLogout']);
Route::post('switch-logout', [LoginController::class, 'switchLogout'])->name('switch.logout');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('throttle:60,1')
    ->get('post-recovery', [LoginController::class, 'recoveryChallenge'])
    ->name('login.post-recovery');
Route::middleware('throttle:60,1')
    ->post('request-new-password', [LoginController::class, 'recoveryChallengePost'])
    ->name('login.request-password');
