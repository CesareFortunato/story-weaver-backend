<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\Story;
use Illuminate\Http\Request;

class NodeController extends Controller
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
    public function create(Story $story)
    {
        return view('nodes.create', compact('story'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Story $story)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
            'image' => 'nullable|image',
            'is_start' => 'nullable|boolean',
        ]);

        $data['story_id'] = $story->id;
        $data['is_start'] = $request->has('is_start');

        // Se questo nodo è start, tolgo lo start dagli altri nodi della stessa storia
        if ($data['is_start']) {
            $story->nodes()->update(['is_start' => false]);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('nodes', 'public');
        }

        $node = Node::create($data);

        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Story $story, Node $node)
    {
        $incomingChoices = \App\Models\Choice::where('next_node_id', $node->id)->get();

        $node->load(['choices.nextNode', 'choices.tokens']);

        return view('nodes.show', compact('story', 'node', 'incomingChoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Story $story, Node $node)
    {
        return view('nodes.edit', compact('story', 'node'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Story $story, Node $node)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
            'image' => 'nullable|image',
            'is_start' => 'nullable|boolean',
        ]);

        $data['is_start'] = $request->has('is_start');

        if ($data['is_start']) {
            $story->nodes()->where('id', '!=', $node->id)->update(['is_start' => false]);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('nodes', 'public');
        }

        $node->update($data);

        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Story $story, Node $node)
    {
        $node->delete();

        return redirect()->route('stories.show', $story);
    }
}
