<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use Orchid\Platform\Http\Controllers\LoginController;

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

Route::get('/health', function (Request $request) {
    return [
        'status' => "ok"
    ];
})->name("health");

Route::get('/build', function (Request $request) {
    return [
        'version' => file_get_contents('../VERSION'),
        'rev' => env('GIT_COMMIT')
    ];
})->name("build");


Route::get('/checkout/{productId}', [\App\Http\Controllers\ShopController::class, 'index']);
Route::post('/create-order', [\App\Http\Controllers\ShopController::class, 'createOrder']);
Route::post('/payment', [\App\Http\Controllers\ShopController::class, 'payment']);


Route::get('/report', function (\Illuminate\Http\Request $request) {
    $reportId = $request->query('reportId');

    if ($reportId != null) {
       $storage = \Illuminate\Support\Facades\Storage::disk('public');

       $report = \App\Models\ShopReports::where('report_id', $reportId);

       if ($report->exists()) {
           $data = $report->firstOrFail();
           if ($storage->exists($data->attachment_id)) {
               return response()->download(storage_path('app/public/' . $data->attachment_id));
           } else {
               return response()->json([
                   'error' => 'Not found in the storage.'
               ]);
           }
       } else {
           return response()->json([
               'error' => 'No records in database for ' .  $reportId
           ]);
       }
    } else {
        return response()->json([
            'error' => 'The reportId cannot be null.'
        ]);
    }
})->middleware('auth')->name('api.shop.report');
