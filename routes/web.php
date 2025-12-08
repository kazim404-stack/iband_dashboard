<?php

use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\AdminForgotPasswordController;
use App\Http\Controllers\backend\AdminResetPasswordController;
use App\Http\Controllers\backend\AttributeController;
use App\Http\Controllers\backend\AttributeValueController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\ContactController;
use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\CurrencyController;
use App\Http\Controllers\backend\GeneralController;
use App\Http\Controllers\backend\GeneralSettingController;
use App\Http\Controllers\backend\LoadParentCategoryController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\PhoneController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProductImageController;
use App\Http\Controllers\backend\ProductVariantController;
use App\Http\Controllers\backend\ReviewController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\backend\SliderImageController;
use App\Http\Controllers\backend\StockControllre;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\LoadCategoryToProductController;
use App\Http\Controllers\ProfileController;

use App\Models\AttributeValue;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Route;
// Route::get('/get-currency', function () {
//     return response()->json([
//         'currency_code' => session('currency_code', 'USD'),
//     ]);
// });

// Route::get('/set-currency', [GeneralController::class, 'setCurrency'])->name('set.currency');


Route::get('/', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/auth', [AdminController::class, 'auth'])->name('admin.auth');

// Reset password route


    // Forgot password form
    Route::get('forgot-password', [AdminForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    // Send reset email
    Route::post('forgot-password', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    // Reset password form
    Route::get('reset-password/{token}', [AdminResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    // Update password
    Route::post('reset-password', [AdminResetPasswordController::class, 'reset'])
        ->name('password.update');







// backend routes
Route::prefix('admin')->as('admin.')->middleware(['admin', 'CheckAdminStatus'])->group(function () {
    // admin auth routes
    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AdminController::class, 'showChangePasswordForm'])->name('changePassword.create');
    Route::post('/change-password', [AdminController::class, 'changePassword'])->name('changePassword.update');
    Route::get('/change-profile', [AdminController::class, 'chageProfileForm'])->name('changeProfile.create');
    Route::post('/change-profile', [AdminController::class, 'chageProfile'])->name('changeProfile.update');
    // admin creae and delete routes
    Route::get('admin/index', [AdminController::class, 'adminList'])->name('admin.list');
    Route::get('admin/create', [AdminController::class, 'adminCreate'])->name('admin.create');
    Route::post('admin/add', [AdminController::class, 'addAdmin'])->name('admin.add');
    Route::delete('admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
    Route::get('admin/change/status', [AdminController::class, 'changeStatus'])->name('admin.changeStatus');







    Route::resource('general-settings', GeneralSettingController::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('phones', PhoneController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('slider-images', SliderImageController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('load-category', [LoadParentCategoryController::class, 'loadParentSelect'])->name('categories.load.parent.select');
    Route::resource('products.images', ProductImageController::class);
    Route::resource('products', ProductController::class);
    Route::get('load-category-to-product', [LoadCategoryToProductController::class, 'loasCategory'])->name('load.category.to.product');
    Route::resource('attributes', AttributeController::class);
    Route::resource('attribute-values', AttributeValueController::class);
    Route::resource('product-variants', ProductVariantController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('stock', StockControllre::class);
    Route::resource('coupons', CouponController::class);
    // route orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}/order', [OrderController::class, 'updateDeliveredAtDate'])->name('orders.updateDeliveredAtDate');
    Route::delete('orders/{order}/order', [OrderController::class, 'delete'])->name('orders.delete');
    // route reviews
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}/{status}', [ReviewController::class, 'toggleReviewStatus'])->name('reviews.toggleReviewStatus');
    Route::delete('reviews/{review}', [ReviewController::class, 'delete'])->name('reviews.delete');
    // users
    Route::get('users/index', [UserController::class, 'index'])->name('users.index');
    Route::delete('users/{user}', [UserController::class, 'delete'])->name('users.delete');
});
