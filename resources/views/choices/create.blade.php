@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Aggiungi scelta</h1>

        <p class="page-subtitle">
            Nodo di partenza: <strong>{{ $node->title ?? 'Nodo senza titolo' }}</strong>.
            La scelta porta il giocatore verso un altro nodo e può assegnare token.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form creazione nuova choice --}}
        <form method="POST" action="{{ route('stories.nodes.choices.store', [$story, $node]) }}">
            @csrf

            {{-- Campo testo della scelta --}}
            <div class="form-group">
                <label>Testo scelta</label>

                <input
                    type="text"
                    name="text"
                    value="{{ old('text') }}"
                    required
                    placeholder="Es. Apri la porta"
                >

                {{-- Mostra eventuali errori di validazione --}}
                @error('text')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Select per scegliere il nodo di destinazione --}}
            <div class="form-group">
                <label>Nodo di destinazione</label>

                <select name="next_node_id">

                    {{-- Choice senza destinazione = nodo finale/interrotto --}}
                    <option value="">-- Nessuna destinazione --</option>

                    {{-- Lista di tutti i nodi disponibili nella storia --}}
                    @foreach ($nodes as $targetNode)

                        <option
                            value="{{ $targetNode->id }}"
                            @selected(old('next_node_id') == $targetNode->id)
                        >
                            {{ $targetNode->title ?? 'Nodo senza titolo' }}
                        </option>

                    @endforeach
                </select>

                {{-- Mostra eventuali errori di validazione --}}
                @error('next_node_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo ordine di visualizzazione --}}
            <div class="form-group">
                <label>Ordine visualizzazione</label>

                <input
                    type="number"
                    name="order"
                    value="{{ old('order', 0) }}"
                    min="0"
                >

                {{-- Mostra eventuali errori di validazione --}}
                @error('order')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sezione token associati alla choice --}}
            <div class="form-group">
                <label>Token assegnati</label>

                {{-- Lista token disponibili --}}
                @forelse ($tokens as $token)

                    <label style="display:block; margin-bottom:8px;">

                        <input
                            type="checkbox"
                            name="tokens[]"
                            value="{{ $token->id }}"
                            @checked(collect(old('tokens', []))->contains($token->id))
                        >

                        {{ $token->name }}
                    </label>

                @empty

                    {{-- Messaggio mostrato se non esistono token --}}
                    <p class="section-help">
                        Nessun token creato per questa storia.
                    </p>

                @endforelse
            </div>

            {{-- Pulsanti finali --}}
            <div class="actions">

                {{-- Salvataggio choice --}}
                <button class="btn">
                    Salva scelta
                </button>

                {{-- Ritorna al dettaglio del nodo --}}
                <a
                    class="btn light"
                    href="{{ route('stories.nodes.show', [$story, $node]) }}"
                >
                    Annulla
                </a>
            </div>
        </form>
    </section>

@endsection