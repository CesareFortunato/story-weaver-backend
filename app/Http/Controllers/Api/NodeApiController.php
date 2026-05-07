<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Support\ApiImage;

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

        // Aggiunge un URL immagine pronto per il frontend, senza modificare il valore originale "image".
        $node->image_url = ApiImage::url($node->image);

        return response()->json([
            'success' => true,
            'data' => $node,
        ]);
    }
}