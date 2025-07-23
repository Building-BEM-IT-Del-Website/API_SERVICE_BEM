<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::prefix('auth')->group(function () {
    // Login route tanpa middleware
    Route::post('login', [AuthController::class, 'login']);

    // Hanya bisa diakses jika sudah login (token valid)
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Route CRUD dengan apiResource
Route::middleware('auth:api')->group(function () {
  // RESTful CRUD user
    Route::apiResource('users', UserController::class)->except(['create', 'edit']);
    // Extra: restore & force-delete
    Route::prefix('users')->group(function () {
        Route::post('restore/{id}', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('force-delete/{id}', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    });
});

