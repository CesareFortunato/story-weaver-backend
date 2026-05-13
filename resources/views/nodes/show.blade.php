@extends('layouts.admin')

@section('content')

    {{-- Header della pagina con titolo del nodo e azioni principali --}}
    <div class="page-header">
        <h1>{{ $node->title ?? 'Nodo senza titolo' }}</h1>

        <p class="page-subtitle">
            Questa pagina mostra il contenuto del nodo, le sue scelte e il flusso narrativo collegato.
        </p>

        {{-- Azioni rapide sul nodo --}}
        <div class="actions">
            <a class="btn light" href="{{ route('stories.show', $story) }}">← Torna alla story</a>

            <a class="btn secondary" href="{{ route('stories.nodes.edit', [$story, $node]) }}">Modifica nodo</a>

            {{-- Link al frontend per testare la storia partendo da questo nodo --}}
            <a class="btn" href="http://localhost:5173/play-node/{{ $node->id }}" target="_blank">
                🎮 Gioca da qui
            </a>

            <a class="btn light" href="{{ route('stories.graph', $story) }}">
                🧭 Torna al Graph Editor
            </a>
        </div>
    </div>

    {{-- Sezione principale della scena --}}
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Scena</h2>
                <p class="section-help">Testo e immagine associati a questo nodo.</p>
            </div>

            {{-- Badge mostrato solo se il nodo è quello iniziale --}}
            @if ($node->is_start)
                <span class="badge start">START</span>
            @endif
        </div>

        {{-- Testo narrativo del nodo --}}
        <p>{{ $node->text }}</p>

        {{-- Immagine del nodo, se presente --}}
        @if ($node->image)
            <img class="preview-image" src="{{ asset('storage/' . $node->image) }}" alt="{{ $node->title }}">
        @endif
    </section>

    {{-- Griglia che mostra collegamenti in entrata e in uscita --}}
    <div class="flow-grid">

        {{-- Scelte di altri nodi che portano al nodo corrente --}}
        <section class="section-card">
            <h2>⬅️ Arriva da</h2>
            <p class="section-help">Nodi e scelte che portano a questa scena.</p>

            @forelse ($incomingChoices as $choice)
                <div class="choice-card">
                    {{-- Nodo di partenza della scelta --}}
                    <strong>{{ $choice->node->title ?? 'Nodo senza titolo' }}</strong>

                    {{-- Testo della scelta che arriva qui --}}
                    <p>tramite scelta: “{{ $choice->text }}”</p>
                </div>
            @empty
                {{-- Stato vuoto se nessun nodo punta a questo --}}
                <div class="empty-state">
                    Nessun nodo porta qui.
                </div>
            @endforelse
        </section>

        {{-- Scelte del nodo corrente e relative destinazioni --}}
        <section class="section-card">
            <h2>➡️ Va a</h2>
            <p class="section-help">Destinazioni raggiungibili dalle scelte di questo nodo.</p>

            @forelse ($node->choices as $choice)
                <div class="choice-card">
                    {{-- Testo della scelta in uscita --}}
                    <strong>{{ $choice->text }}</strong>

                    <p>
                        Destinazione:

                        {{-- Mostra il nodo successivo, se esiste --}}
                        @if ($choice->nextNode)
                            {{ $choice->nextNode->title ?? 'Nodo senza titolo' }}
                        @else
                            <span style="color:#dc2626;">Nessuna destinazione</span>
                        @endif
                    </p>
                </div>
            @empty
                {{-- Stato vuoto se il nodo non ha scelte --}}
                <div class="empty-state">
                    Questo nodo non ha ancora uscite.
                </div>
            @endforelse
        </section>

    </div>

    {{-- Sezione completa delle scelte modificabili --}}
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Scelte</h2>
                <p class="section-help">Le scelte determinano il prossimo nodo e possono dare token al giocatore.</p>
            </div>

            {{-- Pulsanti per creare una singola scelta o più scelte placeholder --}}
            <a class="btn" href="{{ route('stories.nodes.choices.create', [$story, $node]) }}">+ Aggiungi scelta</a>

            <a class="btn light" href="{{ route('stories.nodes.choices.bulk-create', [$story, $node]) }}">
                + Crea più scelte
            </a>
        </div>

        {{-- Lista delle scelte del nodo --}}
        @forelse ($node->choices as $choice)
            <article class="choice-card">
                <h3>{{ $choice->text }}</h3>

                {{-- Metadati della scelta --}}
                <div class="node-meta">
                    <span class="badge">Ordine: {{ $choice->order }}</span>

                    @if ($choice->nextNode)
                        <span class="badge">Va a: {{ $choice->nextNode->title ?? 'Nodo senza titolo' }}</span>
                    @else
                        <span class="badge">Nessuna destinazione</span>
                    @endif
                </div>

                {{-- Token collegati alla scelta, se presenti --}}
                @if ($choice->tokens->isNotEmpty())
                    <p><strong>Token dati:</strong></p>

                    @foreach ($choice->tokens as $token)
                        <span class="token-preview">
                            {{-- Immagine del token, se presente --}}
                            @if ($token->image)
                                <img src="{{ asset('storage/' . $token->image) }}" width="24">
                            @endif

                            {{ $token->name }}
                        </span>
                    @endforeach
                @endif

                {{-- Azioni sulla singola scelta --}}
                <div class="actions">
                    <a class="btn light" href="{{ route('stories.nodes.choices.edit', [$story, $node, $choice]) }}">
                        Modifica
                    </a>

                    {{-- Form di eliminazione scelta --}}
                    <form
                        class="inline-form"
                        action="{{ route('stories.nodes.choices.destroy', [$story, $node, $choice]) }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        {{-- Confirm per evitare eliminazioni accidentali --}}
                        <button class="btn danger" onclick="return confirm('Vuoi davvero eliminare questa scelta?')">
                            Elimina
                        </button>
                    </form>
                </div>
            </article>
        @empty
            {{-- Stato vuoto se il nodo non ha ancora scelte --}}
            <div class="empty-state">
                <strong>Nessuna scelta creata.</strong>
                <p>Aggiungi almeno una scelta per collegare questo nodo al resto della storia.</p>
            </div>
        @endforelse
    </section>

@endsection