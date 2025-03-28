<?php

use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AdminBlogCommentController;
use App\Http\Controllers\AdminBlogReactionController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogReactionController;
use App\Http\Controllers\RoleController;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\User;


// Authentication
require __DIR__.'/auth.php';

// Metadata
Route::get('/metadata', function() {
    return response()->json([
        'phpVersion' => phpversion(),
        'laravelVersion' => app()->version(),
    ]);
});

// We need to prefix our routes to allow CORS calls (see config/cors.php)
Route::prefix('api')->group(function () { 

    // Index page
    Route::get('/blogs', [BlogController::class, 'index']);

    Route::group(['middleware' => 'auth'], function() {
        // Profile
        Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
        Route::post('/update-password', [ProfileController::class, 'updatePassword']);
        Route::post('/delete-account', [ProfileController::class, 'deleteAccount']);

        // Blog
        Route::post('/blog/create', [BlogController::class, 'store']);
        Route::get('/blog', [BlogController::class, 'show']);
        Route::patch('/blog', [BlogController::class, 'edit']);
        Route::delete('/blog', [BlogController::class, 'destroy']);
        Route::post('/blog', [BlogController::class, 'update']);
    
        // Comments
        Route::post('/blog/comment', [BlogCommentController::class, 'store']);
        Route::patch('/blog/comment', [BlogCommentController::class, 'update']);
        Route::delete('/blog/comment', [BlogCommentController::class, 'destroy']);
    
        // Reaction
        Route::post('blog/reaction', [BlogReactionController::class, 'toggle_reaction']);

        // Roles
        Route::get('/auth-roles', [RoleController::class, 'get_auth_roles']); // auth only
        Route::get('/auth-permissions', [RoleController::class, 'get_auth_permissions']); // auth only
        Route::get('/user-roles', [RoleController::class, 'get_user_roles']); // user selection
        Route::get('/user-permissions', [RoleController::class, 'get_user_permissions']); // user selection
    });

    // Admin
    Route::group(['middleware' => ['auth', 'role:admin']], function() {
        // Index page
        Route::get('/admin', function() {
            return response()->json([
                'users' => User::latest()->take(7)->get(),
                'blogs' => Blog::latest()->with('comments')->take(7)->get(),
            ]);
        });

        Route::get('/admin/users', function() {
            return response()->json([
                'users' => User::all()
            ]);
        });

        // Permission routes
        Route::get('/admin/get-permissions', [RoleController::class, 'get_all_permissions']);
        Route::post('/admin/users/permission', [RoleController::class, 'save_user_permissions']);
        Route::post('/admin/users/admin', [RoleController::class, 'toggle_admin_role']);

        // User
        Route::get('/admin/users', [AdminUserController::class, 'index']);
        Route::post('/admin/user', [AdminUserController::class, 'store']);
        Route::get('/admin/user', [AdminUserController::class, 'show']);
        Route::patch('/admin/user', [AdminUserController::class, 'update']);
        Route::delete('/admin/user', [AdminUserController::class, 'destroy']);

        // Blog
        Route::post('/admin/blogs', [AdminBlogController::class, 'store']);
        Route::patch('/admin/blogs', [AdminBlogController::class, 'update']);
        Route::delete('/admin/blogs', [AdminBlogController::class, 'destroy']);

        // Comments
        Route::get('/admin/blogs/comments', [AdminBlogCommentController::class, 'index']);
        Route::get('/admin/blogs/comment', [AdminBlogCommentController::class, 'show']);
        Route::patch('/admin/blogs/comment', [AdminBlogCommentController::class, 'update']);
        Route::delete('/admin/blogs/comment', [AdminBlogCommentController::class, 'destroy']);

        // Reactions
        Route::get('/admin/blogs/reactions', [AdminBlogReactionController::class, 'index']);
        Route::get('/admin/blogs/reaction', [AdminBlogReactionController::class, 'show']);
        Route::patch('/admin/blogs/reaction', [AdminBlogReactionController::class, 'update']);
        Route::delete('/admin/blogs/reaction', [AdminBlogReactionController::class, 'destroy']);
    });
});
