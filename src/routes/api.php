<?php

use App\Http\Controllers\CalendarController;
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

Route::middleware('auth:sanctum')->post('/calendar/events', [CalendarController::class, 'fetchEvents'])
    ->name('platform.api.calendar.events');

Route::middleware('auth:sanctum')->post('/calendar/events', [CalendarController::class, 'fetchEvents']);

Route::middleware('auth:sanctum')->post('/calendar/add', [CalendarController::class, 'addEvent']);
Route::middleware('auth:sanctum')->post('/calendar/update', [CalendarController::class, 'updateEvent']);
Route::middleware('auth:sanctum')->post('/calendar/remove', [CalendarController::class, 'removeEvent']);
