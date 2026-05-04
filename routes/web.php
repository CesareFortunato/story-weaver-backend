<?php

use App\Http\Controllers\NodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoryController;
use App\Models\Story;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('stories', StoryController::class);
    Route::resource('stories.nodes', NodeController::class);
});

require __DIR__ . '/auth.php';
