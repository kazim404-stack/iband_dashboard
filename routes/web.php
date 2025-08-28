<?php

use App\Http\Controllers\backend\ContactController;
use App\Http\Controllers\backend\GeneralSettingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.layouts.admin_master');
});

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
});

require __DIR__ . '/auth.php';
