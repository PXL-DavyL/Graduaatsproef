<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
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

    Route::group(['middleware' => 'auth'], function() {
        // Profile
        Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
        Route::post('/update-password', [ProfileController::class, 'updatePassword']);
        Route::post('/delete-account', [ProfileController::class, 'deleteAccount']);

        // Role
        Route::get('/user-roles', [RoleController::class, 'get_user_roles']);
        Route::get('/user-permissions', [RoleController::class, 'get_user_permissions']);
    });
});
