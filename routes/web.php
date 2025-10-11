<?php

use App\Http\Controllers\backend\AttributeController;
use App\Http\Controllers\backend\AttributeValueController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\ContactController;
use App\Http\Controllers\backend\GeneralSettingController;
use App\Http\Controllers\backend\LoadParentCategoryController;
use App\Http\Controllers\backend\PhoneController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProductImageController;
use App\Http\Controllers\backend\ProductVariantController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\backend\SliderImageController;
use App\Http\Controllers\LoadCategoryToProductController;
use App\Http\Controllers\ProfileController;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard.dashboard');
})->name('da');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


// backend routes
Route::prefix('admin')->as('admin.')->group(function () {
    Route::resource('general-settings', GeneralSettingController::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('phones', PhoneController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('slider-images', SliderImageController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('load-category', [LoadParentCategoryController::class, 'loadParentSelect'])->name('categories.load.parent.select');
    Route::resource('products.images',ProductImageController::class);
    Route::resource('products',ProductController::class);
    Route::get('load-category-to-product', [LoadCategoryToProductController::class,'loasCategory'])->name('load.category.to.product');
    Route::resource('attributes',AttributeController::class);
    Route::resource('attribute-values',AttributeValueController::class);
    Route::resource('product-variants',ProductVariantController::class);

});

require __DIR__ . '/auth.php';
