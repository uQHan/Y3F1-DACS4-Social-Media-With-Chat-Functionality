<?php

use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\EditProfileController;
use App\Http\Controllers\Client\PostController;
use App\Http\Controllers\Client\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [PostController::class, 'index'])
    ->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/edit-profile', [EditProfileController::class, 'update'])->name('profile.update');
    Route::delete('/edit-profile', [EditProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::post('/post-post', [PostController::class, 'store'])->name('post.post');
    Route::post('/post/{id}/comment', [CommentController::class, 'store'])->name('post.comment');
    Route::post('/post/{id}/like', [PostController::class, 'toggleLike'])->name('post.like');
});

require __DIR__ . '/auth.php';
