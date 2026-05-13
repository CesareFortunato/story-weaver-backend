<?php

use App\Http\Controllers\NodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoryApiController;
use App\Http\Controllers\Api\NodeApiController;
use App\Http\Controllers\Api\StoryGraphController;

// =========================
// STORY API
// =========================

// Restituisce la lista di tutte le stories disponibili.
Route::get('/stories', [StoryApiController::class, 'index']);

// Restituisce il dettaglio completo di una singola story.
Route::get('/stories/{story}', [StoryApiController::class, 'show']);

// Restituisce il nodo iniziale della storia,
// usato dal frontend per iniziare l'avventura.
Route::get('/stories/{story}/start', [StoryApiController::class, 'start']);


// =========================
// NODE API
// =========================

// Restituisce il dettaglio completo di un nodo,
// incluse choices, token e nodo successivo.
Route::get('/nodes/{node}', [NodeApiController::class, 'show']);


// =========================
// GRAPH API
// =========================

// Restituisce il grafo della storia nel formato richiesto da React Flow.
Route::get('/stories/{story}/graph', [StoryGraphController::class, 'show']);


// =========================
// NODE POSITION API
// =========================

// Aggiorna la posizione di un nodo nel graph editor.
// Usato quando un nodo viene trascinato nel canvas React Flow.
Route::patch('/nodes/{node}/position', [NodeController::class, 'updatePosition']);