<?php

use App\Http\Controllers\frontend\HomeApiController;
use App\Http\Middleware\LocalizationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API works!']);
});

 Route::middleware([LocalizationMiddleware::class])->group(function () {
    Route::get('slider',[HomeApiController::class,'slider'])->name('home.slider');
    Route::get('general-setting',[HomeApiController::class,'generalSetting'])->name('home.general.setting');
 });
