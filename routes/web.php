<?php

use App\Events\MessageSent;
use App\Http\Controllers\Client\ChatController;
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

Route::middleware(['auth','online'])->group(function () {
    Route::get('/chatroom', [ChatController::class, 'index'])->name('chat');
    
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/edit-profile', [EditProfileController::class, 'update'])->name('profile.update');
    Route::delete('/edit-profile', [EditProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/{id}', [ProfileController::class, 'other'])->name('profile.other');

    Route::post('/post/post', [PostController::class, 'store'])->name('post.post');
    Route::post('/post/{id}/comment', [CommentController::class, 'store'])->name('post.comment');
    Route::post('/post/destroy', [CommentController::class, 'destroy'])->name('post.destroy');

    Route::get('/broadcast', function () {
        broadcast(new MessageSent(2,1,null));
    });
});

require __DIR__ . '/auth.php';
