<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;

class StoryGraphController extends Controller
{
    // Restituisce la struttura grafica di una storia per React Flow.
    public function show(Story $story)
    {
        // Carica i nodi della storia insieme alle relative scelte.
        // In questo modo abbiamo già tutti i dati necessari per creare grafo e collegamenti.
        $story->load(['nodes.choices']);

        // Trasforma i nodi Laravel nel formato richiesto da React Flow.
        $nodes = $story->nodes->map(function ($node) {
            return [
                // React Flow richiede che l'id sia una stringa.
                'id' => (string) $node->id,

                // Posizione del nodo nel canvas.
                // Se non è ancora stata salvata, parte da 0,0.
                'position' => [
                    'x' => $node->position_x ?? 0,
                    'y' => $node->position_y ?? 0,
                ],

                // Dati personalizzati passati al componente del nodo nel frontend.
                'data' => [
                    // Se il nodo è iniziale aggiungiamo una stella nel label.
                    'label' => $node->is_start
                        ? '⭐ ' . $node->title
                        : $node->title,

                    // Passiamo anche il nodo completo per usarlo nel componente React.
                    'node' => $node,
                ],
            ];
        })->values();

        // Trasforma le scelte in collegamenti/frecce del grafo.
        $edges = $story->nodes
            // flatMap serve perché ogni nodo può generare più collegamenti.
            ->flatMap(function ($node) {
                return $node->choices

                    // Considera solo le scelte che puntano davvero a un nodo successivo.
                    ->filter(fn($choice) => $choice->next_node_id)

                    // Ogni scelta diventa una edge di React Flow.
                    ->map(function ($choice) use ($node) {
                        return [
                            // Id univoco della freccia.
                            'id' => 'choice-' . $choice->id,

                            // Nodo di partenza della freccia.
                            'source' => (string) $node->id,

                            // Nodo di arrivo della freccia.
                            'target' => (string) $choice->next_node_id,

                            // Testo mostrato sulla freccia.
                            'label' => $choice->text,

                            // Rende la freccia animata nel frontend.
                            'animated' => true,
                        ];
                    });
            })
            // Reindicizza la collection per ottenere un array pulito.
            ->values();

        // Restituisce nodi e collegamenti nel formato usato da React Flow.
        return response()->json([
            'success' => true,
            'data' => [
                'nodes' => $nodes,
                'edges' => $edges,
            ],
        ]);
    }
}