<?php

use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('stories.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('stories', StoryController::class);

    Route::resource('stories.nodes', NodeController::class)->except(['index']);

    Route::resource('stories.tokens', TokenController::class)->except(['index', 'show']);

    Route::resource('stories.nodes.choices', ChoiceController::class)->except(['index', 'show']);
});

require __DIR__ . '/auth.php';