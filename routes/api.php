<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\MessageController;


Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);


    Route::get('/messages/{contactId}', [MessageController::class, 'index']); // fetch messages
    Route::post('/messages', [MessageController::class, 'store']);            // send message
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead']); // mark read

});

