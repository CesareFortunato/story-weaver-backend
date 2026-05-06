<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoryApiController;
use App\Http\Controllers\Api\NodeApiController;

Route::get('/stories', [StoryApiController::class, 'index']);
Route::get('/stories/{story}', [StoryApiController::class, 'show']);
Route::get('/stories/{story}/start', [StoryApiController::class, 'start']);

Route::get('/nodes/{node}', [NodeApiController::class, 'show']);