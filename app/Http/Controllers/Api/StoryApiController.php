<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;

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

        return response()->json([
            'success' => true,
            'data' => $story,
        ]);
    }

    public function start(Story $story)
    {
        $startNode = $story->nodes()
            ->where('is_start', true)
            ->with(['choices.tokens', 'choices.nextNode'])
            ->first();

        if (!$startNode) {
            return response()->json([
                'success' => false,
                'message' => 'Questa storia non ha un nodo iniziale.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $startNode,
        ]);
    }
}