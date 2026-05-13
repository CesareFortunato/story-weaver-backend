@extends('layouts.admin')

@section('content')

    {{-- Header della pagina con titolo, descrizione e azione principale --}}
    <div class="page-header">
        <h1>Stories</h1>

        <p class="page-subtitle">
            Gestisci tutte le avventure create con StoryWeaver. Apri una storia per creare nodi, token e collegamenti
            narrativi.
        </p>

        {{-- Pulsante per creare una nuova storia --}}
        <div class="actions">
            <a class="btn" href="{{ route('stories.create') }}">+ Nuova Story</a>
        </div>
    </div>

    {{-- Sezione archivio delle storie --}}
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Archivio storie</h2>

                <p class="section-help">
                    Ogni story contiene i nodi, le scelte e i token della sua avventura.
                </p>
            </div>
        </div>

        {{-- Cicla tutte le storie presenti nel database --}}
        @forelse ($stories as $story)
            <article class="node-card">

                {{-- Titolo della storia --}}
                <h3>{{ $story->title }}</h3>

                {{-- Descrizione della storia, oppure testo di fallback --}}
                <p>{{ $story->description ?: 'Nessuna descrizione inserita.' }}</p>

                {{-- Metadati rapidi della storia --}}
                <div class="node-meta">
                    <span class="badge">ID story: {{ $story->id }}</span>

                    {{-- Usa nodes_count se già caricato dal controller, altrimenti conta i nodi --}}
                    <span class="badge">Nodi: {{ $story->nodes_count ?? $story->nodes()->count() }}</span>

                    {{-- Usa tokens_count se già caricato dal controller, altrimenti conta i token --}}
                    <span class="badge">Token: {{ $story->tokens_count ?? $story->tokens()->count() }}</span>
                </div>

                {{-- Azioni disponibili sulla singola storia --}}
                <div class="actions">

                    {{-- Apre il dettaglio della storia --}}
                    <a class="btn" href="{{ route('stories.show', $story) }}">
                        Apri story
                    </a>

                    {{-- Apre il form di modifica --}}
                    <a class="btn light" href="{{ route('stories.edit', $story) }}">
                        Modifica
                    </a>

                    {{-- Form di eliminazione della storia --}}
                    <form class="inline-form" action="{{ route('stories.destroy', $story) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        {{-- Confirm per evitare eliminazioni accidentali --}}
                        <button
                            class="btn danger"
                            onclick="return confirm('Vuoi davvero eliminare questa storia? Verranno eliminati anche nodi, scelte e token collegati.')"
                        >
                            Elimina
                        </button>
                    </form>
                </div>
            </article>
        @empty

            {{-- Stato vuoto se non esistono ancora storie --}}
            <div class="empty-state">
                <strong>Nessuna storia presente.</strong>

                <p>Crea la tua prima avventura interattiva e inizia ad aggiungere nodi narrativi.</p>
            </div>
        @endforelse
    </section>

@endsection