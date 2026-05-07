@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>{{ $node->title ?? 'Nodo senza titolo' }}</h1>

        <p class="page-subtitle">
            Questa pagina mostra il contenuto del nodo, le sue scelte e il flusso narrativo collegato.
        </p>

        <div class="actions">
            <a class="btn light" href="{{ route('stories.show', $story) }}">← Torna alla story</a>
            <a class="btn secondary" href="{{ route('stories.nodes.edit', [$story, $node]) }}">Modifica nodo</a>
            <a class="btn" href="/play/{{ $node->id }}" target="_blank">🎮 Gioca da qui</a>
        </div>
    </div>

    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Scena</h2>
                <p class="section-help">Testo e immagine associati a questo nodo.</p>
            </div>

            @if ($node->is_start)
                <span class="badge start">START</span>
            @endif
        </div>

        <p>{{ $node->text }}</p>

        @if ($node->image)
            <img class="preview-image" src="{{ asset('storage/' . $node->image) }}" alt="{{ $node->title }}">
        @endif
    </section>

    <div class="flow-grid">

        <section class="section-card">
            <h2>⬅️ Arriva da</h2>
            <p class="section-help">Nodi e scelte che portano a questa scena.</p>

            @forelse ($incomingChoices as $choice)
                <div class="choice-card">
                    <strong>{{ $choice->node->title ?? 'Nodo senza titolo' }}</strong>
                    <p>tramite scelta: “{{ $choice->text }}”</p>
                </div>
            @empty
                <div class="empty-state">
                    Nessun nodo porta qui.
                </div>
            @endforelse
        </section>

        <section class="section-card">
            <h2>➡️ Va a</h2>
            <p class="section-help">Destinazioni raggiungibili dalle scelte di questo nodo.</p>

            @forelse ($node->choices as $choice)
                <div class="choice-card">
                    <strong>{{ $choice->text }}</strong>

                    <p>
                        Destinazione:
                        @if ($choice->nextNode)
                            {{ $choice->nextNode->title ?? 'Nodo senza titolo' }}
                        @else
                            <span style="color:#dc2626;">Nessuna destinazione</span>
                        @endif
                    </p>
                </div>
            @empty
                <div class="empty-state">
                    Questo nodo non ha ancora uscite.
                </div>
            @endforelse
        </section>

    </div>

    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Scelte</h2>
                <p class="section-help">Le scelte determinano il prossimo nodo e possono dare token al giocatore.</p>
            </div>

            <a class="btn" href="{{ route('stories.nodes.choices.create', [$story, $node]) }}">+ Aggiungi scelta</a>
        </div>

        @forelse ($node->choices as $choice)
            <article class="choice-card">
                <h3>{{ $choice->text }}</h3>

                <div class="node-meta">
                    <span class="badge">Ordine: {{ $choice->order }}</span>

                    @if ($choice->nextNode)
                        <span class="badge">Va a: {{ $choice->nextNode->title ?? 'Nodo senza titolo' }}</span>
                    @else
                        <span class="badge">Nessuna destinazione</span>
                    @endif
                </div>

                @if ($choice->tokens->isNotEmpty())
                    <p><strong>Token dati:</strong></p>

                    @foreach ($choice->tokens as $token)
                        <span class="token-preview">
                            @if ($token->image)
                                <img src="{{ asset('storage/' . $token->image) }}" width="24">
                            @endif
                            {{ $token->name }}
                        </span>
                    @endforeach
                @endif

                <div class="actions">
                    <a class="btn light" href="{{ route('stories.nodes.choices.edit', [$story, $node, $choice]) }}">Modifica</a>

                    <form class="inline-form" action="{{ route('stories.nodes.choices.destroy', [$story, $node, $choice]) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn danger" onclick="return confirm('Vuoi davvero eliminare questa scelta?')">
                            Elimina
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <strong>Nessuna scelta creata.</strong>
                <p>Aggiungi almeno una scelta per collegare questo nodo al resto della storia.</p>
            </div>
        @endforelse
    </section>

@endsection