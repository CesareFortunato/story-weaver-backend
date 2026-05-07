<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Support\ApiImage;


class StoryApiController extends Controller
{
    public function index()
    {
        $stories = Story::select('id', 'title', 'description')->get();

        return response()->json([
            'success' => true,
            'data' => $stories,
        ]);
    }

    public function show(Story $story)
    {
        $story->load([
            'nodes:id,story_id,title,text,image,is_start',
            'tokens:id,story_id,name,image',
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

        return response()->json([
            'success' => true,
            'data' => $story,
        ]);
    }

    public function start(Story $story)
    {
        $startNode = $story->nodes()
            ->where('is_start', true)
            ->with([
                'choices' => function ($query) {
                    $query->orderBy('order')->orderBy('id');
                },
                'choices.tokens',
                'choices.nextNode:id,title',
            ])
            ->first();

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

        return response()->json([
            'success' => true,
            'data' => $startNode,
        ]);
    }
}