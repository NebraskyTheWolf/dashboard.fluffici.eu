<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

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

Route::get('password-recovery', [LoginController::class, 'password'])
    ->name('api.login.recovery');

Route::get('password-new', [LoginController::class, 'password'])
    ->name('login.new-password');

Route::get('lock', [LoginController::class, 'resetCookieLockMe'])->name('login.lock');
Route::get('switch-logout', [LoginController::class, 'switchLogout']);
Route::post('switch-logout', [LoginController::class, 'switchLogout'])->name('switch.logout');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('post-recovery', [LoginController::class, 'recoveryChallenge']);
Route::middleware('throttle:60,1')
    ->post('request-new-password', [LoginController::class, 'recoveryChallengePost'])
    ->name('login.request-password');
