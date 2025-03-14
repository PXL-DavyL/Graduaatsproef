<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Authentication
require __DIR__.'/auth.php';

// Index page
Route::get('/', function () {
    return Inertia::render('Home', [
        'blogs' => Blog::with('poster')->paginate(12),
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
});
Route::resource('blog', BlogController::class, ['only' => ['index', 'show']]);

// Admin
Route::middleware('auth', 'verified', 'role:admin')->group(function () {
    Route::get('/admin', function () {
        return Inertia::render('Admin/Dashboard', [
                'users' => User::latest()->take(7)->get(),
                'blogs' => Blog::latest()->take(7)->get(),
        ]);
    })->name('admin.index');        
});

