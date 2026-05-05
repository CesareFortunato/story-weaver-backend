<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = Story::latest()->get();

        return view('stories.index', compact('stories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Story::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('stories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Story $story)
    {
        $story->load(['nodes.choices', 'tokens']);

    $warnings = [];

    // 1. Nessun nodo start
    if (!$story->nodes->contains('is_start', true)) {
        $warnings[] = '⚠️ La storia non ha un nodo iniziale';
    }

    // 2. Nodi senza scelte
    foreach ($story->nodes as $node) {
        if ($node->choices->isEmpty()) {
            $warnings[] = "⚠️ Il nodo '{$node->title}' non ha scelte";
        }

        foreach ($node->choices as $choice) {
            if (!$choice->next_node_id) {
                $warnings[] = "⚠️ La scelta '{$choice->text}' non ha destinazione";
            }
        }
    }

    return view('stories.show', compact('story', 'warnings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Story $story)
    {
        return view('stories.edit', compact('story'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Story $story)
    {
        $story->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('stories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Story $story)
    {
        $story->delete();

        return redirect()->route('stories.index');
    }
}
