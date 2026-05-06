@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Stories</h1>
        <p class="page-subtitle">
            Gestisci tutte le avventure create con StoryWeaver. Apri una storia per creare nodi, token e collegamenti
            narrativi.
        </p>

        <div class="actions">
            <a class="btn" href="{{ route('stories.create') }}">+ Nuova Story</a>
        </div>
    </div>

    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>Archivio storie</h2>
                <p class="section-help">
                    Ogni story contiene i nodi, le scelte e i token della sua avventura.
                </p>
            </div>
        </div>

        @forelse ($stories as $story)
            <article class="node-card">
                <h3>{{ $story->title }}</h3>

                <p>{{ $story->description ?: 'Nessuna descrizione inserita.' }}</p>

                <div class="node-meta">
                    <span class="badge">ID story: {{ $story->id }}</span>
                    <span class="badge">Nodi: {{ $story->nodes_count ?? $story->nodes()->count() }}</span>
                    <span class="badge">Token: {{ $story->tokens_count ?? $story->tokens()->count() }}</span>
                </div>

                <div class="actions">
                    <a class="btn" href="{{ route('stories.show', $story) }}">Apri story</a>
                    <a class="btn light" href="{{ route('stories.edit', $story) }}">Modifica</a>

                    <form class="inline-form" action="{{ route('stories.destroy', $story) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn danger">Elimina</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="empty-state">
                <strong>Nessuna storia presente.</strong>
                <p>Crea la tua prima avventura interattiva e inizia ad aggiungere nodi narrativi.</p>
            </div>
        @endforelse
    </section>

@endsection