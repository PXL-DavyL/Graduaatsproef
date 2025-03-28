<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

Route::prefix('api')->group(function () {
    Route::get('/csrf-token', function() {
        return response()->json(['token' => csrf_token()]);
    });
    
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot_pass', [AuthController::class, 'forgot_pass']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) { return $request->user(); });
});