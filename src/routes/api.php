<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ShortUrlController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    
    // Short URL routes
    Route::prefix('short-url')->group(function () {
        Route::post('/', [ShortUrlController::class, 'store']);
        Route::post('/custom', [ShortUrlController::class, 'storeCustom']);
    });

    // QR Code routes
    Route::prefix('qr')->group(function () {
        Route::post('/generate', [QrCodeController::class, 'generate']);
        Route::get('/{identifier}/image', [QrCodeController::class, 'getImage']);
    });
    
});