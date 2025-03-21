<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});


Route::get('/metadata', function() {
    return response()->json([
        'phpVersion' => phpversion(),
        'laravelVersion' => app()->version(),
    ]);
});


Route::prefix('api')->group(function () {
    Route::get('/csrf-token', function() {
        return response()->json(['token' => csrf_token()]);
    });
    
    // auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot_pass', [AuthController::class, 'forgot_pass']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) { return $request->user(); });

    // Profile
    Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->middleware('auth');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->middleware('auth');
    Route::post('/delete-account', [ProfileController::class, 'deleteAccount'])->middleware('auth');
});
