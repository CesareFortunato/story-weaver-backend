<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Models\Node;
use App\Models\Story;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{
    /**
     * Mostra la lista delle choices.
     * Al momento non viene usato perché le choices vengono gestite dal dettaglio del nodo.
     */
    public function index()
    {
        //
    }

    /**
     * Mostra il form per creare una nuova choice collegata a un nodo.
     */
    public function create(Story $story, Node $node)
    {
        // Recupera tutti i nodi della storia.
        // Servono per scegliere il nodo successivo della choice.
        $nodes = $story->nodes()->orderBy('title')->get();

        // Recupera tutti i token della storia.
        // Servono per selezionare eventuali requisiti della choice.
        $tokens = $story->tokens()->orderBy('name')->get();

        return view('choices.create', compact('story', 'node', 'nodes', 'tokens'));
    }

    /**
     * Salva una nuova choice nel database.
     */
    public function store(Request $request, Story $story, Node $node)
    {
        // Valida i dati ricevuti dal form.
        $data = $request->validate([
            'text' => 'required|string|max:255',
            'next_node_id' => 'nullable|exists:nodes,id',
            'order' => 'nullable|integer|min:0',
            'tokens' => 'nullable|array',
            'tokens.*' => 'exists:tokens,id',
        ]);

        // Crea la choice collegandola al nodo corrente.
        $choice = Choice::create([
            'node_id' => $node->id,
            'text' => $data['text'],
            'next_node_id' => $data['next_node_id'] ?? null,
            'order' => $data['order'] ?? 0,
        ]);

        // Sincronizza i token richiesti dalla choice nella tabella pivot.
        // Se non ci sono token selezionati, viene salvato un array vuoto.
        $choice->tokens()->sync($data['tokens'] ?? []);

        // Torna al dettaglio del nodo.
        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Mostra il dettaglio di una choice.
     * Al momento non viene usato perché la choice si gestisce da create/edit.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Mostra il form per modificare una choice esistente.
     */
    public function edit(Story $story, Node $node, Choice $choice)
    {
        // Recupera i nodi disponibili come possibili destinazioni.
        $nodes = $story->nodes()->orderBy('title')->get();

        // Recupera i token disponibili come possibili requisiti.
        $tokens = $story->tokens()->orderBy('name')->get();

        // Carica i token già associati alla choice,
        // così nel form risultano già selezionati.
        $choice->load('tokens');

        return view('choices.edit', compact('story', 'node', 'choice', 'nodes', 'tokens'));
    }

    /**
     * Aggiorna una choice esistente nel database.
     */
    public function update(Request $request, Story $story, Node $node, Choice $choice)
    {
        // Valida i dati ricevuti dal form di modifica.
        $data = $request->validate([
            'text' => 'required|string|max:255',
            'next_node_id' => 'nullable|exists:nodes,id',
            'order' => 'nullable|integer|min:0',
            'tokens' => 'nullable|array',
            'tokens.*' => 'exists:tokens,id',
        ]);

        // Aggiorna i campi principali della choice.
        $choice->update([
            'text' => $data['text'],
            'next_node_id' => $data['next_node_id'] ?? null,
            'order' => $data['order'] ?? 0,
        ]);

        // Aggiorna i token collegati alla choice nella tabella pivot.
        $choice->tokens()->sync($data['tokens'] ?? []);

        // Torna al dettaglio del nodo.
        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Elimina una choice dal database.
     */
    public function destroy(Story $story, Node $node, Choice $choice)
    {
        // Elimina la choice selezionata.
        $choice->delete();

        // Torna al dettaglio del nodo.
        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Mostra il form per creare più choices contemporaneamente.
     */
    public function bulkCreate(Story $story, Node $node)
    {
        return view('choices.bulk-create', compact('story', 'node'));
    }

    /**
     * Crea più choices vuote collegate allo stesso nodo.
     */
    public function bulkStore(Request $request, Story $story, Node $node)
    {
        // Valida il numero di choices da creare.
        // Il massimo è 20 per evitare creazioni massive accidentali.
        $data = $request->validate([
            'amount' => 'required|integer|min:1|max:20',
        ]);

        // Crea il numero richiesto di choices con dati provvisori.
        for ($i = 1; $i <= $data['amount']; $i++) {
            Choice::create([
                'node_id' => $node->id,
                'text' => 'Nuova scelta ' . $i,
                'next_node_id' => null,
                'order' => 0,
            ]);
        }

        // Dopo la creazione torna al dettaglio del nodo,
        // dove le choices potranno essere modificate una alla volta.
        return redirect()->route('stories.nodes.show', [$story, $node]);
    }
}