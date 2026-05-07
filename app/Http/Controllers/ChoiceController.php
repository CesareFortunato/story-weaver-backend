<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Models\Node;
use App\Models\Story;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Story $story, Node $node)
    {
        $nodes = $story->nodes()->orderBy('title')->get();
        $tokens = $story->tokens()->orderBy('name')->get();

        return view('choices.create', compact('story', 'node', 'nodes', 'tokens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Story $story, Node $node)
    {
        $data = $request->validate([
            'text' => 'required|string|max:255',
            'next_node_id' => 'nullable|exists:nodes,id',
            'order' => 'nullable|integer|min:0',
            'tokens' => 'nullable|array',
            'tokens.*' => 'exists:tokens,id',
        ]);

        $choice = Choice::create([
            'node_id' => $node->id,
            'text' => $data['text'],
            'next_node_id' => $data['next_node_id'] ?? null,
            'order' => $data['order'] ?? 0,
        ]);

        $choice->tokens()->sync($data['tokens'] ?? []);

        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Story $story, Node $node, Choice $choice)
    {
        $nodes = $story->nodes()->orderBy('title')->get();
        $tokens = $story->tokens()->orderBy('name')->get();

        $choice->load('tokens');

        return view('choices.edit', compact('story', 'node', 'choice', 'nodes', 'tokens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Story $story, Node $node, Choice $choice)
    {
        $data = $request->validate([
            'text' => 'required|string|max:255',
            'next_node_id' => 'nullable|exists:nodes,id',
            'order' => 'nullable|integer|min:0',
            'tokens' => 'nullable|array',
            'tokens.*' => 'exists:tokens,id',
        ]);

        $choice->update([
            'text' => $data['text'],
            'next_node_id' => $data['next_node_id'] ?? null,
            'order' => $data['order'] ?? 0,
        ]);

        $choice->tokens()->sync($data['tokens'] ?? []);

        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Story $story, Node $node, Choice $choice)
    {
        $choice->delete();

        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    public function bulkCreate(Story $story, Node $node)
    {
        return view('choices.bulk-create', compact('story', 'node'));
    }

    public function bulkStore(Request $request, Story $story, Node $node)
    {
        $data = $request->validate([
            'amount' => 'required|integer|min:1|max:20',
        ]);

        for ($i = 1; $i <= $data['amount']; $i++) {
            Choice::create([
                'node_id' => $node->id,
                'text' => 'Nuova scelta ' . $i,
                'next_node_id' => null,
                'order' => 0,
            ]);
        }

        return redirect()->route('stories.nodes.show', [$story, $node]);
    }
}
