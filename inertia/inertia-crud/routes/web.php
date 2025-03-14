<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Models\Blog;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'blogs' => Blog::with('poster')->paginate(12),
    ]);
})->name('home');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('blog', BlogController::class, ['except' => ['index', 'show']]);
});
Route::resource('blog', BlogController::class, ['only' => ['index', 'show']]);

require __DIR__.'/auth.php';
