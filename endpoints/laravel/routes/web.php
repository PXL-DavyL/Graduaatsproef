<?php

use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AdminBlogCommentController;
use App\Http\Controllers\AdminBlogReactionController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogReactionController;
use App\Http\Controllers\RoleController;
use App\Models\Blog;
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

    // Blog
    Route::get('/blog/index', [BlogController::class, 'index']);
    Route::post('/blog/create', [BlogController::class, 'store']);
    Route::get('/blog/show', [BlogController::class, 'show']);
    Route::patch('/blog/edit', [BlogController::class, 'edit']);
    Route::delete('/blog/destroy', [BlogController::class, 'destroy']);
    Route::post('/blog/add-view', [BlogController::class, 'add_view']);

    // Comments
    Route::post('/comment/store', [CommentController::class, 'store']);
    Route::patch('/comment/update', [CommentController::class, 'update']);
    Route::delete('/comment/destroy', [CommentController::class, 'destroy']);

    // Reaction
    Route::post('reaction_toggle', [BlogReactionController::class, 'toggle_reaction']);

    Route::group(['middleware' => 'auth'], function() {
        // Profile
        Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
        Route::post('/update-password', [ProfileController::class, 'updatePassword']);
        Route::post('/delete-account', [ProfileController::class, 'deleteAccount']);

        // Role
        Route::get('/auth-roles', [RoleController::class, 'get_auth_roles']); // auth only
        Route::get('/auth-permissions', [RoleController::class, 'get_auth_permissions']); // auth only
        Route::get('/user-roles', [RoleController::class, 'get_user_roles']); // user selection
        Route::get('/user-permissions', [RoleController::class, 'get_user_permissions']); // user selection
    });

    Route::group(['middleware' => ['auth', 'role:admin']], function() {
        // Admin stuff goes ehre

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

        // Role
        Route::post('/admin/user/set-permission', [RoleController::class, 'save_user_permissions']);
        Route::post('/admin/user/toggle-admin', [RoleController::class, 'toggle_admin_role']);
        Route::get('/admin/user/permissions', [RoleController::class, 'get_all_permissions']);

        // User
        Route::get('/admin/users', [AdminUserController::class, 'index']);
        Route::post('/admin/user', [AdminUserController::class, 'store']);
        Route::get('/admin/user', [AdminUserController::class, 'show']);
        Route::patch('/admin/user', [AdminUserController::class, 'update']);
        Route::delete('/admin/user', [AdminUserController::class, 'destroy']);

        // Blog
        Route::post('/admin/blog', [AdminBlogController::class, 'store']);
        Route::patch('/admin/blog', [AdminBlogController::class, 'update']);
        Route::delete('/admin/blog', [AdminBlogController::class, 'destroy']);

        // Comments
        Route::get('/admin/blog/comments', [AdminBlogCommentController::class, 'index']);
        Route::get('/admin/blog/comment', [AdminBlogCommentController::class, 'show']);
        Route::patch('/admin/blog/comment', [AdminBlogCommentController::class, 'update']);
        Route::delete('/admin/blog/comment', [AdminBlogCommentController::class, 'destroy']);

        // Reactions
        Route::get('/admin/blog/reactions', [AdminBlogReactionController::class, 'index']);
        Route::get('/admin/blog/reaction', [AdminBlogReactionController::class, 'show']);
        Route::patch('/admin/blog/reaction', [AdminBlogReactionController::class, 'update']);
        Route::delete('/admin/blog/reaction', [AdminBlogReactionController::class, 'destroy']);
    });

});
