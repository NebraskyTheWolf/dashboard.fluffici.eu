<?php

use App\Http\Controllers\IntegrationsController;
use App\Http\Controllers\Versioning;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/kofi', [IntegrationsController::class, "kofiCallback"]);
Route::post('/api/webhook/github', [Versioning::class, 'index']);
