<?php

use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

// =====================================================
// HOME
// =====================================================

// Reindirizza automaticamente la homepage alla lista delle stories.
Route::get('/', function () {
    return redirect()->route('stories.index');
});


// =====================================================
// ROTTE PROTETTE DA AUTENTICAZIONE
// middleware: auth
// =====================================================

Route::middleware('auth')->group(function () {

    // =====================================================
    // PROFILO UTENTE
    // =====================================================

    // Mostra il form profilo.
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // Aggiorna i dati del profilo.
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Elimina l'account utente.
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // =====================================================
    // STORIES
    // =====================================================

    // Resource controller completo per le stories.
    // Genera automaticamente:
    // index, create, store, show, edit, update, destroy
    Route::resource('stories', StoryController::class);


    // =====================================================
    // GRAPH EDITOR
    // =====================================================

    // Mostra la pagina React Flow del graph editor.
    Route::get('/stories/{story}/graph', [StoryController::class, 'graph'])
        ->name('stories.graph');

    // Restituisce i dati JSON del grafo.
    Route::get('/stories/{story}/graph-data', [StoryController::class, 'graphData'])
        ->name('stories.graph-data');


    // =====================================================
    // POSIZIONE NODI NEL GRAFO
    // =====================================================

    // Salva la posizione di un nodo trascinato nel graph editor.
    Route::patch('/nodes/{node}/position', [NodeController::class, 'updatePosition'])
        ->name('nodes.update-position');


    // =====================================================
    // CREAZIONE MULTIPLA NODI
    // =====================================================

    // Mostra il form per creare più nodi.
    Route::get('/stories/{story}/nodes/bulk-create', [NodeController::class, 'bulkCreate'])
        ->name('stories.nodes.bulk-create');

    // Salva più nodi contemporaneamente.
    Route::post('/stories/{story}/nodes/bulk-store', [NodeController::class, 'bulkStore'])
        ->name('stories.nodes.bulk-store');


    // =====================================================
    // NODES
    // =====================================================

    // Resource controller nested dei nodi.
    // except(['index']) perché i nodi vengono mostrati dentro la story.
    Route::resource('stories.nodes', NodeController::class)
        ->except(['index']);


    // =====================================================
    // CREAZIONE MULTIPLA TOKEN
    // =====================================================

    // Mostra il form per creare più token.
    Route::get('/stories/{story}/tokens/bulk-create', [TokenController::class, 'bulkCreate'])
        ->name('stories.tokens.bulk-create');

    // Salva più token contemporaneamente.
    Route::post('/stories/{story}/tokens/bulk-store', [TokenController::class, 'bulkStore'])
        ->name('stories.tokens.bulk-store');


    // =====================================================
    // TOKENS
    // =====================================================

    // Resource controller nested dei token.
    // index e show non servono perché i token vengono gestiti nella pagina story.
    Route::resource('stories.tokens', TokenController::class)
        ->except(['index', 'show']);


    // =====================================================
    // CREAZIONE MULTIPLA CHOICES
    // =====================================================

    // Mostra il form per creare più scelte.
    Route::get('/stories/{story}/nodes/{node}/choices/bulk-create', [ChoiceController::class, 'bulkCreate'])
        ->name('stories.nodes.choices.bulk-create');

    // Salva più scelte contemporaneamente.
    Route::post('/stories/{story}/nodes/{node}/choices/bulk-store', [ChoiceController::class, 'bulkStore'])
        ->name('stories.nodes.choices.bulk-store');


    // =====================================================
    // CHOICES
    // =====================================================

    // Resource controller nested delle choices.
    // index e show non servono perché le scelte vengono mostrate nella pagina nodo.
    Route::resource('stories.nodes.choices', ChoiceController::class)
        ->except(['index', 'show']);
});


// =====================================================
// AUTH ROUTES (Laravel Breeze)
// =====================================================

// Importa tutte le rotte di autenticazione:
// login, register, logout, reset password, verifica email, ecc.
require __DIR__ . '/auth.php';