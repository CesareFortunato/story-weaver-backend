<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;

class StoryGraphController extends Controller
{
    public function show(Story $story)
    {
        // Carica i nodi della storia con le relative scelte
        $story->load(['nodes.choices']);

        // Trasforma i nodi Laravel nel formato richiesto da React Flow
        $nodes = $story->nodes->map(function ($node) {
            return [
                'id' => (string) $node->id,
                'position' => [
                    'x' => $node->position_x ?? 0,
                    'y' => $node->position_y ?? 0,
                ],
                'data' => [
                    'label' => $node->is_start
                        ? '⭐ ' . $node->title
                        : $node->title,
                    'node' => $node,
                ],
            ];
        })->values();

        // Trasforma le scelte in collegamenti/frecce del grafo
        $edges = $story->nodes
            ->flatMap(function ($node) {
                return $node->choices
                    ->filter(fn($choice) => $choice->next_node_id)
                    ->map(function ($choice) use ($node) {
                        return [
                            'id' => 'choice-' . $choice->id,
                            'source' => (string) $node->id,
                            'target' => (string) $choice->next_node_id,
                            'label' => $choice->text,
                            'animated' => true,
                        ];
                    });
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'nodes' => $nodes,
                'edges' => $edges,
            ],
        ]);
    }
}