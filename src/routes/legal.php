<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/privacy', function (Request $request) {



    return view('pages.privacy');
});

Route::get('/tos', function (Request $request) {
    return view('pages.tos');
});
