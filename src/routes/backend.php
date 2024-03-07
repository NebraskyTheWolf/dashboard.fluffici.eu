<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoucherController;

use Illuminate\Support\Facades\Route;

Route::middleware('auth.session')->get('/report', [ReportController::class, 'index'])->name('api.shop.report');
Route::middleware('auth.session')->get('/voucher', [VoucherController::class, 'index'])->name('api.shop.voucher');
