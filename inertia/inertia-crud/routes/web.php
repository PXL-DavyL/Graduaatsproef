<?php

use App\Http\Controllers\AdminBlogCommentController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AdminBlogReactionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\BlogReactionController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

// Authentication
require __DIR__.'/auth.php';

// Index page
Route::get('/', function () {
    return Inertia::render('Home', [
        'blogs' => Blog::with('poster', 'comments', 'reactions')->paginate(12),
    ]);
})->name('home');

// Profile
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Blog
Route::group(['middleware' => 'auth'], function () {
    Route::resource('blog', BlogController::class, ['except' => ['index', 'show']]);
    Route::resource('blog.comment', BlogCommentController::class)->only(['store', 'update', 'destroy']);

    // Reactions
    Route::post('/blog/{blog}/reaction', [BlogReactionController::class, 'toggle_reaction'])->name('blog.reaction.toggle');
});
Route::resource('blog', BlogController::class, ['only' => ['index', 'show']]);

// Admin
Route::middleware('auth', 'verified', 'role:admin')->group(function () {
    // Index page
    Route::get('/admin', function () {
        return Inertia::render('Admin/Dashboard', [
                'users' => User::latest()->take(7)->get(),
                'blogs' => Blog::with('poster', 'comments', 'reactions')->take(7)->get(),
        ]);
    })->name('admin.index');       

    // Permission routes
    Route::get('/admin/get-permissions', [AdminPermissionController::class, 'index'])->name('admin.permissions');
    Route::post('/admin/users/{user}/permission', [AdminPermissionController::class, 'update'])->name('admin.users.toggle_permission');
    Route::post('/admin/users/{user}/admin', [AdminPermissionController::class, 'toggle_admin'])->name('admin.users.toggle_admin');

    // Resource routes (blogs/users)
    Route::resource('/admin/users', AdminUserController::class)->names('admin.users');
    Route::resource('/admin/blogs', AdminBlogController::class)->names('admin.blogs');

    // Comments
    Route::get('/admin/blogs/{blog}/comment/', [AdminBlogCommentController::class, 'show'])->name('admin.comments.show');
    Route::get('/admin/blogs/{blog}/comment/{comment}/edit', [AdminBlogCommentController::class, 'edit'])->name('admin.comments.edit');
    Route::patch('/admin/blogs/{blog}/comment/{comment}', [AdminBlogCommentController::class, 'update'])->name('admin.comments.update');
    Route::delete('/admin/blogs/{blog}/comment/{comment}', [AdminBlogCommentController::class, 'destroy'])->name('admin.comments.destroy');

    // Reactions
    Route::get('/admin/blogs/{blog}/reaction/', [AdminBlogReactionController::class, 'show'])->name('admin.reactions.show');
    Route::get('/admin/blogs/{blog}/reaction/{reaction}/edit', [AdminBlogReactionController::class, 'edit'])->name('admin.reactions.edit');
    Route::patch('/admin/blogs/{blog}/reaction/{reaction}', [AdminBlogReactionController::class, 'update'])->name('admin.reactions.update');
    Route::delete('/admin/blogs/{blog}/reaction/{reaction}', [AdminBlogReactionController::class, 'destroy'])->name('admin.reactions.destroy');
});
