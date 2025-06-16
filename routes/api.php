<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TaskController;

Route::get('/up', function (Request $request) {
    return 'healthy';
});

// prefix set on app.php / middleware
// guest routes
Route::prefix('v1')->group(function () {

Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');


// Authenticated routes
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

    // task routes
    Route::apiResource('tasks', TaskController::class);
    Route::patch('tasks/{id}/complete', [TaskController::class, 'complete']);

});

});

