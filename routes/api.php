<?php

use App\Http\Controllers\CartItemController;
use App\Http\Controllers\frontend\AuthController;
use App\Http\Controllers\frontend\CouponController;
use App\Http\Controllers\frontend\HomeApiController;
use App\Http\Controllers\frontend\OrderController;
use App\Http\Controllers\frontend\ReviewController;
use App\Http\Middleware\LocalizationMiddleware;
use Illuminate\Support\Facades\Route;

// Route::middleware([LocalizationMiddleware::class])->group(function () {
//     Route::get('slider', [HomeApiController::class, 'slider'])->name('home.slider');
//     Route::get('general-setting', [HomeApiController::class, 'generalSetting'])->name('home.general.setting');
//     Route::get('category', [HomeApiController::class, 'category'])->name('home.category');
//     Route::get('product', [HomeApiController::class, 'product'])->name('home.product');
//     Route::get('product/{category_id}', [HomeApiController::class, 'productFilterByCatId'])->name('home.productFilterByCatId');
// });
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/orders', [OrderController::class, 'userOrders']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Order routes
    Route::post('order/store', [OrderController::class, 'store'])->name('order.store');
    // coupon routes
    Route::post('coupon/apply', [CouponController::class, 'applyCoupon'])->name('coupon.apply');
    // Review routes
    Route::post('review/store', [ReviewController::class, 'store'])->name('review.store');
    Route::put('review/update', [ReviewController::class, 'update'])->name('review.update');
    Route::post('review/delete', [ReviewController::class, 'delete'])->name('review.delete');
});

Route::middleware(['web', LocalizationMiddleware::class])->group(function () {
    Route::get('slider', [HomeApiController::class, 'slider'])->name('home.slider');
    Route::get('general-setting', [HomeApiController::class, 'generalSetting'])->name('home.general.setting');
    Route::get('category', [HomeApiController::class, 'category'])->name('home.category');
    Route::get('product', [HomeApiController::class, 'product'])->name('home.product');
    Route::get('productGroupBy', [HomeApiController::class, 'productGroupBy'])->name('home.productGroupBy');
    Route::get('product/{category_id}', [HomeApiController::class, 'productFilterByCatId'])->name('home.productFilterByCatId');
    Route::get('product_details/{product_id}', [HomeApiController::class, 'productDetails'])->name('home.productDetails');

    Route::get('set-currency', [HomeApiController::class, 'setCurrency'])->name('home.set.currency');
    Route::get('get-currency', [HomeApiController::class, 'getCurrency'])->name('home.get.currency');
    // Cart (public)
    Route::post('/cart/add', [CartItemController::class, 'add']);
    Route::get('/cart', [CartItemController::class, 'index']);
    Route::post('/cart/remove/{id}', [CartItemController::class, 'remove']);
    Route::post('/cart/update/{id}', [CartItemController::class, 'update']);
    Route::post('/cart/merge', [CartItemController::class, 'mergeGuestCart']); // after login
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
