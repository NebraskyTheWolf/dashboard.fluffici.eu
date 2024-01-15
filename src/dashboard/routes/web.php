<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Shop\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/events/interested/counts', [EventController::class, 'interestedCounts'])->name('events.interested.counts');
Route::post('/events/interested', [EventController::class, 'interested'])->name('events.interested');

Route::get('/posts', [PostsController::class, 'index'])->name("posts");
Route::post('/posts/{postid}/add/comment', [PostsController::class, 'comment'])->name("posts.comment");

Route::get('/pages/{pageslug}', [PagesController::class, 'index'])->name('pages');

Route::group(['namespace' => 'Shop', 'prefix' => 'shop'], function () {
    Route::get('/', [ShopController::class, 'index'])->name("shop.home");
    Route::get('/product/{productSlug}', [ShopController::class, 'product'])->name("shop.product");
    Route::get('/product/{productSlug}/image/{size}', [ShopController::class, 'productImage'])->name("shop.product.image");
    Route::get('/category/{categoryid}', [ShopController::class, 'category'])->name("shop.category");

    Route::get('/cart', [ShopController::class, 'cart']);
    Route::post('/cart/confirm', [ShopController::class, 'confirmCart']);
    Route::post('/cart/remove', [ShopController::class, 'removeItem']);
    Route::post('/cart/clear', [ShopController::class, 'clearCart']);
    Route::post('/cart/add', [ShopController::class, 'addItem']);

    Route::get('/success', [ShopController::class, 'paymentSuccess']);
    Route::get('/failed/{message}', [ShopController::class, 'paymentFailed']);

    Route::post('/payment/paypal/notification', [ShopController::class, 'paypalCallback']);
    Route::post('/payment/paysafecard/notification', [ShopController::class, 'paysafeCallback']);

    Route::get('/products/search', function (Request $request) {
        return response()->json([
            'status' => true,
            'products' => array_map(function ($user) {
                return [
                'product' => $user['name'],
                'img' => url('/shop/product/') . $user['name'] . '/image/32',
                'url' => url('/shop/product/' . $user['name'])
                ];
            }, \App\Models\ShopProducts::where('name', 'LIKE', '%' . $_GET['q'] . '%')->get()->toArray())
        ]);
    });
});

Route::get('/health', function (Request $request) {
    return [
        'status' => "ok",
        'version' => file_get_contents('../VERSION'),
    ];
});