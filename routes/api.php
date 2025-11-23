<?php

use App\Http\Controllers\backend\GeneralController;
use App\Http\Controllers\frontend\AuthController;
use App\Http\Controllers\frontend\HomeApiController;
use App\Http\Controllers\frontend\OrderController;
use App\Http\Middleware\LocalizationMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route::middleware([LocalizationMiddleware::class])->group(function () {
//     Route::get('slider', [HomeApiController::class, 'slider'])->name('home.slider');
//     Route::get('general-setting', [HomeApiController::class, 'generalSetting'])->name('home.general.setting');
//     Route::get('category', [HomeApiController::class, 'category'])->name('home.category');
//     Route::get('product', [HomeApiController::class, 'product'])->name('home.product');
//     Route::get('product/{category_id}', [HomeApiController::class, 'productFilterByCatId'])->name('home.productFilterByCatId');
// });
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('order/store', [OrderController::class, 'store'])->name('order.store');
});

Route::middleware(['web', LocalizationMiddleware::class])->group(function () {
    Route::get('slider', [HomeApiController::class, 'slider'])->name('home.slider');
    Route::get('general-setting', [HomeApiController::class, 'generalSetting'])->name('home.general.setting');
    Route::get('category', [HomeApiController::class, 'category'])->name('home.category');
    Route::get('product', [HomeApiController::class, 'product'])->name('home.product');
    Route::get('product/{category_id}', [HomeApiController::class, 'productFilterByCatId'])->name('home.productFilterByCatId');
    Route::get('product_details/{product_id}', [HomeApiController::class, 'productDetails'])->name('home.productDetails');

    Route::get('set-currency', [HomeApiController::class, 'setCurrency'])->name('home.set.currency');
    Route::get('get-currency', [HomeApiController::class, 'getCurrency'])->name('home.get.currency');
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
