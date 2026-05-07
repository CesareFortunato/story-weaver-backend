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

    Route::get('/stories/{story}/nodes/bulk-create', [NodeController::class, 'bulkCreate'])
        ->name('stories.nodes.bulk-create');

    Route::post('/stories/{story}/nodes/bulk-store', [NodeController::class, 'bulkStore'])
        ->name('stories.nodes.bulk-store');

    Route::resource('stories.nodes', NodeController::class)->except(['index']);

    Route::get('/stories/{story}/tokens/bulk-create', [TokenController::class, 'bulkCreate'])
        ->name('stories.tokens.bulk-create');

    Route::post('/stories/{story}/tokens/bulk-store', [TokenController::class, 'bulkStore'])
        ->name('stories.tokens.bulk-store');

    Route::resource('stories.tokens', TokenController::class)->except(['index', 'show']);

    Route::get('/stories/{story}/nodes/{node}/choices/bulk-create', [ChoiceController::class, 'bulkCreate'])
        ->name('stories.nodes.choices.bulk-create');

    Route::post('/stories/{story}/nodes/{node}/choices/bulk-store', [ChoiceController::class, 'bulkStore'])
        ->name('stories.nodes.choices.bulk-store');

    Route::resource('stories.nodes.choices', ChoiceController::class)->except(['index', 'show']);
});

require __DIR__ . '/auth.php';