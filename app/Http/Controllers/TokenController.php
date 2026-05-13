<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * Mostra la lista dei token.
     * Al momento non viene usato perché i token vengono gestiti dal dettaglio della storia.
     */
    public function index()
    {
        //
    }

    /**
     * Mostra il form per creare un nuovo token collegato a una storia.
     */
    public function create(Story $story)
    {
        return view('tokens.create', compact('story'));
    }

    /**
     * Salva un nuovo token nel database.
     */
    public function store(Request $request, Story $story)
    {
        // Valida i dati ricevuti dal form.
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        // Collega il token alla storia corrente.
        $data['story_id'] = $story->id;

        // Se è stata caricata un'immagine,
        // la salva nello storage pubblico nella cartella "tokens".
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tokens', 'public');
        }

        // Crea il token nel database.
        Token::create($data);

        // Torna al dettaglio della storia.
        return redirect()->route('stories.show', $story);
    }

    /**
     * Mostra il dettaglio di un token.
     * Al momento non viene usato perché il token viene gestito da create/edit.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Mostra il form per modificare un token esistente.
     */
    public function edit(Story $story, Token $token)
    {
        return view('tokens.edit', compact('story', 'token'));
    }

    /**
     * Aggiorna un token esistente nel database.
     */
    public function update(Request $request, Story $story, Token $token)
    {
        // Valida i dati ricevuti dal form di modifica.
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        // Se viene caricata una nuova immagine,
        // la salva e aggiorna il percorso nel database.
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tokens', 'public');
        }

        // Aggiorna il token con i nuovi dati.
        $token->update($data);

        // Torna al dettaglio della storia.
        return redirect()->route('stories.show', $story);
    }

    /**
     * Elimina un token dal database.
     */
    public function destroy(Story $story, Token $token)
    {
        // Elimina il token selezionato.
        $token->delete();

        // Torna al dettaglio della storia.
        return redirect()->route('stories.show', $story);
    }

    /**
     * Mostra il form per creare più token contemporaneamente.
     */
    public function bulkCreate(Story $story)
    {
        return view('tokens.bulk-create', compact('story'));
    }

    /**
     * Crea più token vuoti collegati alla stessa storia.
     */
    public function bulkStore(Request $request, Story $story)
    {
        // Valida il numero di token da creare.
        // Il massimo è 20 per evitare creazioni massive accidentali.
        $data = $request->validate([
            'amount' => 'required|integer|min:1|max:20',
        ]);

        // Crea il numero richiesto di token con dati provvisori.
        for ($i = 1; $i <= $data['amount']; $i++) {

            Token::create([
                'story_id' => $story->id,
                'name' => 'Nuovo token ' . $i,
            ]);
        }

        // Dopo la creazione torna al dettaglio della storia,
        // dove i token potranno essere modificati uno alla volta.
        return redirect()->route('stories.show', $story);
    }
}