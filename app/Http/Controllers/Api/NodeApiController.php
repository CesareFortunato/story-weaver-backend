<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Support\ApiImage;

class NodeApiController extends Controller
{
    // Metodo che mostra un singolo nodo con tutte le sue relazioni utili.
    public function show(Node $node)
    {
        // Carica le relazioni collegate al nodo evitando query aggiuntive (eager loading).
        $node->load([

            // Carica le choices ordinate per campo "order" e poi per id.
            'choices' => function ($query) {
                $query->orderBy('order')->orderBy('id');
            },

            // Carica i token richiesti da ogni choice.
            'choices.tokens',

            // Carica il nodo successivo collegato alla choice.
            // Recuperiamo solo id e title per ottimizzare la query.
            'choices.nextNode:id,title',
        ]);

        // Aggiunge un URL immagine pronto per il frontend sul nodo.
        $node->image_url = ApiImage::url($node->image);

        // Per ogni choice:
        // aggiungiamo ai token il relativo URL immagine completo.
        $node->choices->each(function ($choice) {

            $choice->tokens->each(function ($token) {

                $token->image_url = ApiImage::url($token->image);

            });
        });

        // Restituisce una risposta JSON standardizzata.
        return response()->json([
            'success' => true,
            'data' => $node,
        ]);
    }
}