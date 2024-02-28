<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FirebasePushController;
use App\Http\Controllers\PaymentController;

use Illuminate\Support\Facades\Route;

// Management Backend

Route::middleware('throttle')->get('/api/device/authorization', [DeviceController::class, 'index']);

Route::middleware('auth.api:platform.shop.orders.read')->get('/api/order', [PaymentController::class, 'fetchOrder']);
Route::middleware('auth.api:platform.shop.orders.write')->get('/api/order/payment', [PaymentController::class, 'index']);
Route::middleware(['auth.api:platform.shop.orders.read', 'throttle'])->get('/api/device/orders', [DeviceController::class, 'orders']);
Route::middleware(['auth.api:platform.systems.eshop', 'throttle'])->get('/api/device/customers', [DeviceController::class, 'customers']);

Route::middleware(['auth.api:platform.shop.products.read', 'throttle'])->get('/api/device/products', [DeviceController::class, 'products']);
Route::middleware(['auth.api:platform.shop.vouchers.read', 'throttle'])->get('/api/order/voucher/info', [DeviceController::class, 'voucherInfo']);

Route::middleware(['auth.api:platform.shop.products.read', 'throttle'])->get('/api/device/fetch/product', [DeviceController::class, 'fetchProduct']);
Route::middleware(['auth.api:platform.shop.products.write', 'throttle'])->get('/api/device/increment/product', [DeviceController::class, 'incrementProduct']);

Route::middleware('auth.api:platform.shop.orders.write')->get('/api/order/payment/refund', [PaymentController::class, 'refund']);
Route::middleware('auth.api:platform.shop.orders.write')->get('/api/order/cancel', [PaymentController::class, 'cancel']);

Route::middleware('auth.api:platform.firebase.token.write')->post('/api/device/firebase/set-token', [FirebasePushController::class, 'setToken'])
    ->name('api.firebase.token');

Route::middleware('auth.api:platform.firebase.notification.ack')->post('/api/device/firebase/notification', [FirebasePushController::class, 'notification'])
    ->name('api.firebase.push');
