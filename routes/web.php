<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware('web')->group(function () {
//     Route::post('/login', [AuthController::class, 'login']);
// });
