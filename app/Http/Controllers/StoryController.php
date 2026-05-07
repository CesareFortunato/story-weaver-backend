<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = Story::withCount(['nodes', 'tokens'])->latest()->get();

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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ambient_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
        ]);

        if ($request->hasFile('ambient_audio')) {
            $data['ambient_audio'] = $request->file('ambient_audio')->store('audio', 'public');
        }

        Story::create($data);

        return redirect()->route('stories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Story $story)
    {
        $story->load([
            'nodes.choices.nextNode',
            'nodes.choices.tokens',
            'tokens'
        ]);

        $warnings = [];

        if ($story->nodes->isEmpty()) {
            $warnings[] = '⚠️ La storia non ha ancora nessun nodo';
        }

        if (!$story->nodes->contains('is_start', true)) {
            $warnings[] = '⚠️ La storia non ha un nodo iniziale';
        }

        foreach ($story->nodes as $node) {
            if ($node->choices->isEmpty()) {
                $warnings[] = "⚠️ Il nodo '{$node->title}' non ha scelte";
            }

            foreach ($node->choices as $choice) {
                if (!$choice->next_node_id) {
                    $warnings[] = "⚠️ La scelta '{$choice->text}' nel nodo '{$node->title}' non ha destinazione";
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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ambient_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
        ]);

        if ($request->hasFile('ambient_audio')) {
            if ($story->ambient_audio) {
                Storage::disk('public')->delete($story->ambient_audio);
            }

            $data['ambient_audio'] = $request->file('ambient_audio')->store('audio', 'public');
        }

        $story->update($data);

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
