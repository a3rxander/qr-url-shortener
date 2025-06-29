<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;


Route::get('/', function () {
    return view('welcome');
});



// Short URL redirect route
Route::get('/s/{identifier}', [RedirectController::class, 'redirect']);