<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoucherController;

use Illuminate\Support\Facades\Route;

Route::middleware('auth.session')->get('/report', [ReportController::class, 'index'])->name('api.shop.report');
Route::middleware('auth.session')->get('/voucher', [VoucherController::class, 'index'])->name('api.shop.voucher');
Route::middleware('auth.session')->get('/product/ean', [ApiController::class, 'fetchEANCode'])->name('api.shop.barcode');

Route::get('/api/generate/order/{order_id}', [VoucherController::class, 'datamatrix'])->middleware('throttle');
