<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;

class NodeApiController extends Controller
{
    public function show(Node $node)
    {
        $node->load([
            'choices' => function ($query) {
                $query->orderBy('order')->orderBy('id');
            },
            'choices.tokens',
            'choices.nextNode:id,title',
        ]);

        return response()->json([
            'success' => true,
            'data' => $node,
        ]);
    }
}