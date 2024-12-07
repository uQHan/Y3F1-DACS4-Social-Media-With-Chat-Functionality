<?php

use App\Http\Controllers\Client\EditProfileController;
use App\Http\Controllers\Client\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/edit-profile', [EditProfileController::class, 'update'])->name('profile.update');
    Route::delete('/edit-profile', [EditProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
});

require __DIR__.'/auth.php';
