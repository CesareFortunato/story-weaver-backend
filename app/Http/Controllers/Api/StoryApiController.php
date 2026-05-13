<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Support\ApiImage;

class StoryApiController extends Controller
{
    // Restituisce l'elenco di tutte le storie disponibili.
    public function index()
    {
        // Recupera solo i campi necessari per la lista,
        // evitando di caricare dati inutili.
        $stories = Story::select('id', 'title', 'description', 'ambient_audio')->get();

        // Per ogni storia aggiunge l'URL completo dell'audio ambientale,
        // così il frontend può usarlo direttamente.
        $stories->transform(function ($story) {
            $story->ambient_audio_url = ApiImage::url($story->ambient_audio);
            return $story;
        });

        // Restituisce una risposta JSON standardizzata.
        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }

    // Restituisce il dettaglio di una singola storia.
    public function show(Story $story)
    {
        // Carica i nodi e i token collegati alla storia.
        // Vengono selezionati solo i campi necessari al frontend.
        $story->load([
            'nodes:id,story_id,title,text,image,is_start',
            'tokens:id,story_id,name,description,image',
        ]);

        // Aggiunge URL immagine utilizzabili dal frontend per ogni nodo.
        $story->nodes->transform(function ($node) {
            $node->image_url = ApiImage::url($node->image);
            return $node;
        });

        // Aggiunge URL immagine utilizzabili dal frontend per ogni token.
        $story->tokens->transform(function ($token) {
            $token->image_url = ApiImage::url($token->image);
            return $token;
        });

        // Aggiunge anche l'URL completo dell'audio ambientale della storia.
        $story->ambient_audio_url = ApiImage::url($story->ambient_audio);

        // Restituisce la storia completa di nodi, token e media pronti per il frontend.
        return response()->json([
            'success' => true,
            'data' => $story,
        ]);
    }

    // Restituisce il nodo iniziale della storia.
    // È l'endpoint usato dal frontend per avviare la partita/lettura.
    public function start(Story $story)
    {
        // Cerca tra i nodi della storia quello segnato come iniziale.
        // Insieme al nodo carica anche choices, token richiesti e nodo successivo.
        $startNode = $story->nodes()
            ->where('is_start', true)
            ->with([
                // Ordina le choices in base al campo "order" e poi per id.
                'choices' => function ($query) {
                    $query->orderBy('order')->orderBy('id');
                },

                // Carica i token richiesti dalle choices.
                'choices.tokens',

                // Carica il nodo successivo collegato alla choice.
                // Vengono presi solo id e title per ottimizzare la risposta.
                'choices.nextNode:id,title',
            ])
            ->first();

        // Se non esiste un nodo iniziale, restituisce errore 404.
        if (!$startNode) {
            return response()->json([
                'success' => false,
                'message' => 'Questa storia non ha un nodo iniziale configurato.',
                'code' => 'START_NODE_NOT_FOUND',
            ], 404);
        }

        // Aggiunge URL immagine pronto per il frontend.
        $startNode->image_url = ApiImage::url($startNode->image);

        // Aggiunge URL immagine pronti per il frontend sui token richiesti dalle choices.
        $startNode->choices->each(function ($choice) {
            $choice->tokens->each(function ($token) {
                $token->image_url = ApiImage::url($token->image);
            });
        });

        // Restituisce sia i dati base della storia,
        // sia il nodo iniziale completo delle sue relazioni.
        return response()->json([
            'success' => true,
            'data' => [
                'story' => [
                    'id' => $story->id,
                    'title' => $story->title,
                    'ambient_audio' => $story->ambient_audio,
                    'ambient_audio_url' => ApiImage::url($story->ambient_audio),
                ],
                'node' => $startNode,
            ],
        ]);
    }
}