<?php

use App\Http\Controllers\NodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoryApiController;
use App\Http\Controllers\Api\NodeApiController;
use App\Http\Controllers\Api\StoryGraphController;


Route::get('/stories', [StoryApiController::class, 'index']);
Route::get('/stories/{story}', [StoryApiController::class, 'show']);
Route::get('/stories/{story}/start', [StoryApiController::class, 'start']);

Route::get('/nodes/{node}', [NodeApiController::class, 'show']);
Route::get('/stories/{story}/graph', [StoryGraphController::class, 'show']);
Route::patch('/nodes/{node}/position', [NodeController::class, 'updatePosition']);