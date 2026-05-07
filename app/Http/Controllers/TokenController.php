<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
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
        return view('tokens.create', compact('story'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Story $story)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
        ]);

        $data['story_id'] = $story->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tokens', 'public');
        }

        Token::create($data);

        return redirect()->route('stories.show', $story);
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
    public function edit(Story $story, Token $token)
    {
        return view('tokens.edit', compact('story', 'token'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Story $story, Token $token)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tokens', 'public');
        }

        $token->update($data);

        return redirect()->route('stories.show', $story);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Story $story, Token $token)
    {
        $token->delete();

        return redirect()->route('stories.show', $story);
    }

    public function bulkCreate(Story $story)
    {
        return view('tokens.bulk-create', compact('story'));
    }

    public function bulkStore(Request $request, Story $story)
    {
        $data = $request->validate([
            'amount' => 'required|integer|min:1|max:20',
        ]);

        for ($i = 1; $i <= $data['amount']; $i++) {

            Token::create([
                'story_id' => $story->id,
                'name' => 'Nuovo token ' . $i,
            ]);
        }

        return redirect()->route('stories.show', $story);
    }
}
