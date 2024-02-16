<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\IntegrationsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Versioning;
use App\Http\Controllers\VoucherController;
use App\Models\LastVersion;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;

use Orchid\Platform\Http\Controllers\AsyncController;
use Orchid\Platform\Http\Controllers\AttachmentController;
use Orchid\Platform\Http\Controllers\IndexController;
use Orchid\Platform\Http\Screens\NotificationScreen;
use Orchid\Platform\Http\Screens\SearchScreen;
use Tabuna\Breadcrumbs\Trail;

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

Route::get('/pages/{slug}', [PagesController::class, 'index']);

Route::prefix('dashboard')->group(function () {
    Route::get('/', [IndexController::class, 'index'])
        ->name('index')
        ->breadcrumbs(fn (Trail $trail) => $trail->push(__('Home'), route('index')));

    Route::screen('search/{query}', SearchScreen::class)
        ->name('search')
        ->breadcrumbs(fn (Trail $trail, string $query) => $trail->parent('index')
            ->push(__('Search'))
            ->push($query));
});

Route::post('async/{screen}/{method?}/{template?}', [AsyncController::class, 'load'])
->name('async');

Route::post('listener/{screen}/{layout}', [AsyncController::class, 'listener'])
->name('async.listener');

Route::prefix('systems')->group(function () {
    Route::post('uploaded', [AttachmentController::class, 'uploaded'])
        ->name('systems.files.uploaded');
});


Route::post('relation', [\Orchid\Platform\Http\Controllers\RelationController::class, 'view'])
    ->name('platform.systems.relation');


Route::screen('notifications/{id?}', NotificationScreen::class)
->name('notifications')
->breadcrumbs(fn (Trail $trail) => $trail->parent('index')
    ->push(__('Notifications')));

Route::post('api/notifications', [NotificationScreen::class, 'unreadNotification'])
->name('api.notifications');

Route::get('/health', function ($request) {
    return response()->json([
        'status' => "ok"
    ]);
})->name("health");

Route::get('/build/{variable?}', function ($request) {
    $version = LastVersion::latest()->first();

    return response()->json([
        'version' => $version->getCurrentVersion(),
        'rev' => $version->getShortCommitId()
    ]);

})->name("build");

Route::middleware('auth.session')->get('/report', [ReportController::class, 'index'])->name('api.shop.report');
Route::middleware('auth.session')->get('/voucher', [VoucherController::class, 'index'])->name('api.shop.voucher');

Route::post('/api/login', [ApiController::class, 'index']);

Route::middleware('auth.api')->get('/api/order', [PaymentController::class, 'fetchOrder']);
Route::middleware('auth.api')->get('/api/order/payment', [PaymentController::class, 'index']);

Route::get('/api/generate/order/{order_id}', [VoucherController::class, 'datamatrix'])->middleware('throttle');

Route::post('/webhook/kofi', [IntegrationsController::class, "kofiCallback"]);

Route::post('/api/webhook/github', [Versioning::class, 'index']);

Route::middleware('throttle')->get('/api/device/authorization', [DeviceController::class, 'index']);
