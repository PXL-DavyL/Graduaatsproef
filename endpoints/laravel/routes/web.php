<?php

use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use App\Models\User;

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
        Route::get('/auth-roles', [RoleController::class, 'get_auth_roles']);
        Route::get('/auth-permissions', [RoleController::class, 'get_auth_permissions']);

        Route::get('/user-roles', [RoleController::class, 'get_user_roles']);
        Route::get('/user-permissions', [RoleController::class, 'get_user_permissions']);
    });

    Route::group(['middleware' => ['auth', 'role:admin']], function() {
        // Admin stuff goes ehre

        Route::get('/admin/users', function() {
            return response()->json([
                'users' => User::all()
            ]);
        });


        // Role
        Route::post('/admin/user/set-permission', [RoleController::class, 'save_user_permissions']);
        Route::post('/admin/user/toggle-admin', [RoleController::class, 'toggle_admin_role']);
        Route::get('/admin/user/permissions', [RoleController::class, 'get_all_permissions']);

        // User
        Route::get('/admin/user', [AdminUserController::class, 'getUser']);
        Route::post('/admin/user', [AdminUserController::class, 'saveUser']);
        Route::delete('/admin/user', [AdminUserController::class, 'destroyUser']);
    });

});
