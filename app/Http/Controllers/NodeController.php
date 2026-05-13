<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\Story;
use Illuminate\Http\Request;
use App\Models\Choice;

class NodeController extends Controller
{
    /**
     * Mostra la lista dei nodi.
     * Al momento non viene usato perché i nodi vengono gestiti dal dettaglio della storia.
     */
    public function index()
    {
        //
    }

    /**
     * Mostra il form per creare un nuovo nodo collegato a una storia.
     */
    public function create(Story $story)
    {
        return view('nodes.create', compact('story'));
    }

    /**
     * Salva un nuovo nodo nel database.
     */
    public function store(Request $request, Story $story)
    {
        // Valida i dati ricevuti dal form.
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
            'image' => 'nullable|image',
            'is_start' => 'nullable|boolean',
        ]);

        // Collega il nuovo nodo alla storia corrente.
        $data['story_id'] = $story->id;

        // Converte la checkbox "is_start" in true/false.
        $data['is_start'] = $request->has('is_start');

        // Se questo nodo viene impostato come iniziale,
        // rimuove il flag "start" dagli altri nodi della stessa storia.
        if ($data['is_start']) {
            $story->nodes()->update(['is_start' => false]);
        }

        // Se è stata caricata un'immagine,
        // la salva nello storage pubblico nella cartella "nodes".
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('nodes', 'public');
        }

        // Crea il nodo nel database.
        $node = Node::create($data);

        // Dopo la creazione porta al dettaglio del nuovo nodo.
        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Mostra il dettaglio di un nodo.
     */
    public function show(Story $story, Node $node)
    {
        // Recupera tutte le scelte di altri nodi che puntano a questo nodo.
        // Serve per sapere da dove è raggiungibile il nodo corrente.
        $incomingChoices = Choice::where('next_node_id', $node->id)->get();

        // Carica le scelte del nodo, il nodo successivo collegato
        // e gli eventuali token richiesti dalle scelte.
        $node->load(['choices.nextNode', 'choices.tokens']);

        return view('nodes.show', compact('story', 'node', 'incomingChoices'));
    }

    /**
     * Mostra il form per modificare un nodo esistente.
     */
    public function edit(Story $story, Node $node)
    {
        return view('nodes.edit', compact('story', 'node'));
    }

    /**
     * Aggiorna un nodo esistente nel database.
     */
    public function update(Request $request, Story $story, Node $node)
    {
        // Valida i dati ricevuti dal form di modifica.
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
            'image' => 'nullable|image',
            'is_start' => 'nullable|boolean',
        ]);

        // Converte la checkbox "is_start" in true/false.
        $data['is_start'] = $request->has('is_start');

        // Se questo nodo viene impostato come iniziale,
        // toglie il flag agli altri nodi della stessa storia.
        if ($data['is_start']) {
            $story->nodes()->where('id', '!=', $node->id)->update(['is_start' => false]);
        }

        // Se viene caricata una nuova immagine,
        // la salva e aggiorna il percorso nel database.
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('nodes', 'public');
        }

        // Aggiorna il nodo con i nuovi dati.
        $node->update($data);

        // Torna al dettaglio del nodo aggiornato.
        return redirect()->route('stories.nodes.show', [$story, $node]);
    }

    /**
     * Elimina un nodo dal database.
     */
    public function destroy(Story $story, Node $node)
    {
        // Elimina il nodo selezionato.
        $node->delete();

        // Dopo l'eliminazione torna al dettaglio della storia.
        return redirect()->route('stories.show', $story);
    }

    /**
     * Mostra il form per creare più nodi contemporaneamente.
     */
    public function bulkCreate(Story $story)
    {
        return view('nodes.bulk-create', compact('story'));
    }

    /**
     * Crea più nodi vuoti collegati alla stessa storia.
     */
    public function bulkStore(Request $request, Story $story)
    {
        // Valida il numero di nodi da creare.
        // Il limite massimo è 20 per evitare creazioni massive accidentali.
        $data = $request->validate([
            'amount' => 'required|integer|min:1|max:20',
        ]);

        // Crea il numero richiesto di nodi con dati provvisori.
        for ($i = 1; $i <= $data['amount']; $i++) {
            Node::create([
                'story_id' => $story->id,
                'title' => 'Nuovo nodo ' . $i,
                'text' => 'Testo da completare',
                'is_start' => false,
            ]);
        }

        // Torna al dettaglio della storia,
        // dove i nodi potranno essere modificati uno alla volta.
        return redirect()->route('stories.show', $story);
    }

    /**
     * Aggiorna la posizione grafica del nodo nel canvas React Flow.
     */
    public function updatePosition(Request $request, Node $node)
    {
        // Valida le coordinate ricevute dal frontend.
        $validated = $request->validate([
            'position_x' => ['required', 'integer'],
            'position_y' => ['required', 'integer'],
        ]);

        // Salva la nuova posizione nel database.
        $node->update($validated);

        // Restituisce una risposta JSON usata dal frontend.
        return response()->json([
            'success' => true,
            'data' => $node,
        ]);
    }
}