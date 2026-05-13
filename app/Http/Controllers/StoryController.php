<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    /**
     * Mostra la lista di tutte le storie.
     */
    public function index()
    {
        // Recupera le storie contando anche quanti nodi e token hanno.
        // latest() le ordina dalla più recente alla più vecchia.
        $stories = Story::withCount(['nodes', 'tokens'])->latest()->get();

        return view('stories.index', compact('stories'));
    }

    /**
     * Mostra il form per creare una nuova storia.
     */
    public function create()
    {
        return view('stories.create');
    }

    /**
     * Salva una nuova storia nel database.
     */
    public function store(Request $request)
    {
        // Valida i dati ricevuti dal form.
        // L'audio ambientale è opzionale e accetta solo mp3, wav o ogg.
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ambient_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
        ]);

        // Se è stato caricato un file audio,
        // lo salva nello storage pubblico nella cartella "audio".
        if ($request->hasFile('ambient_audio')) {
            $data['ambient_audio'] = $request->file('ambient_audio')->store('audio', 'public');
        }

        // Crea la storia nel database.
        Story::create($data);

        // Torna alla lista delle storie.
        return redirect()->route('stories.index');
    }

    /**
     * Mostra il dettaglio di una storia.
     */
    public function show(Story $story)
    {
        // Carica i nodi, le scelte, i nodi successivi,
        // i token richiesti dalle scelte e i token della storia.
        $story->load([
            'nodes.choices.nextNode',
            'nodes.choices.tokens',
            'tokens'
        ]);

        // Array usato per raccogliere eventuali problemi nella struttura della storia.
        $warnings = [];

        // Controlla se la storia non ha ancora nodi.
        if ($story->nodes->isEmpty()) {
            $warnings[] = '⚠️ La storia non ha ancora nessun nodo';
        }

        // Controlla se manca un nodo iniziale.
        if (!$story->nodes->contains('is_start', true)) {
            $warnings[] = '⚠️ La storia non ha un nodo iniziale';
        }

        // Controlla ogni nodo della storia.
        foreach ($story->nodes as $node) {

            // Segnala i nodi senza scelte.
            if ($node->choices->isEmpty()) {
                $warnings[] = "⚠️ Il nodo '{$node->title}' non ha scelte";
            }

            // Controlla ogni scelta del nodo.
            foreach ($node->choices as $choice) {

                // Segnala le scelte che non portano a nessun nodo successivo.
                if (!$choice->next_node_id) {
                    $warnings[] = "⚠️ La scelta '{$choice->text}' nel nodo '{$node->title}' non ha destinazione";
                }
            }
        }

        return view('stories.show', compact('story', 'warnings'));
    }

    /**
     * Mostra la pagina del grafo della storia.
     */
    public function graph(Story $story)
    {
        return view('stories.graph', compact('story'));
    }

    /**
     * Restituisce i dati del grafo in formato JSON per React Flow.
     */
    public function graphData(Story $story)
    {
        // Carica nodi e scelte necessari per costruire il grafo.
        $story->load(['nodes.choices']);

        // Controlla se tutti i nodi sono ancora nella posizione 0,0.
        // In quel caso viene creato un layout iniziale più leggibile.
        $allNodesAreUnplaced = $story->nodes->every(function ($node) {
            return $node->position_x === 0 && $node->position_y === 0;
        });

        // Trasforma i nodi Laravel nel formato richiesto da React Flow.
        $nodes = $story->nodes->values()->map(function ($node, $index) use ($allNodesAreUnplaced) {
            // Distribuisce i nodi in righe e colonne nel layout iniziale.
            $column = $index % 4;
            $row = intdiv($index, 4);

            return [
                // React Flow richiede id in formato stringa.
                'id' => (string) $node->id,

                // Se i nodi non hanno posizione salvata, crea una griglia iniziale.
                // Altrimenti usa le coordinate salvate nel database.
                'position' => [
                    'x' => $allNodesAreUnplaced ? $column * 320 : ($node->position_x ?? 0),
                    'y' => $allNodesAreUnplaced ? $row * 180 : ($node->position_y ?? 0),
                ],

                // Dati personalizzati passati al componente nodo nel frontend.
                'data' => [
                    // Evidenzia il nodo iniziale con una stella.
                    'label' => $node->is_start
                        ? '⭐ ' . $node->title
                        : $node->title,

                    // Passa il nodo completo al componente React.
                    'node' => $node,
                ],
            ];
        })->values();

        // Trasforma le scelte in collegamenti/frecce del grafo.
        $edges = $story->nodes
            // Ogni nodo può generare più collegamenti.
            ->flatMap(function ($node) {
                return $node->choices

                    // Usa solo le scelte che hanno una destinazione.
                    ->filter(fn($choice) => $choice->next_node_id)

                    // Ogni choice diventa una edge per React Flow.
                    ->map(function ($choice) use ($node) {
                        return [
                            // Id univoco del collegamento.
                            'id' => 'choice-' . $choice->id,

                            // Nodo di partenza.
                            'source' => (string) $node->id,

                            // Nodo di arrivo.
                            'target' => (string) $choice->next_node_id,

                            // Testo mostrato sulla freccia.
                            'label' => $choice->text,

                            // Rende la freccia animata nel grafo.
                            'animated' => true,
                        ];
                    });
            })
            // Reindicizza la collection in un array pulito.
            ->values();

        // Restituisce i dati pronti per essere letti da React Flow.
        return response()->json([
            'success' => true,
            'data' => [
                'nodes' => $nodes,
                'edges' => $edges,
            ],
        ]);
    }

    /**
     * Mostra il form per modificare una storia.
     */
    public function edit(Story $story)
    {
        return view('stories.edit', compact('story'));
    }

    /**
     * Aggiorna una storia esistente nel database.
     */
    public function update(Request $request, Story $story)
    {
        // Valida i dati ricevuti dal form.
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ambient_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
        ]);

        // Se viene caricato un nuovo audio ambientale:
        if ($request->hasFile('ambient_audio')) {

            // elimina il vecchio file audio, se esiste.
            if ($story->ambient_audio) {
                Storage::disk('public')->delete($story->ambient_audio);
            }

            // salva il nuovo file audio nello storage pubblico.
            $data['ambient_audio'] = $request->file('ambient_audio')->store('audio', 'public');
        }

        // Aggiorna la storia nel database.
        $story->update($data);

        // Torna alla lista delle storie.
        return redirect()->route('stories.index');
    }

    /**
     * Elimina una storia dal database.
     */
    public function destroy(Story $story)
    {
        // Elimina la storia selezionata.
        $story->delete();

        // Torna alla lista delle storie.
        return redirect()->route('stories.index');
    }
}