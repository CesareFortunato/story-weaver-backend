@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>{{ $story->title }}</h1>
        <p class="page-subtitle">
            {{ $story->description }}
        </p>

        <div class="actions">
            <a class="btn light" href="{{ route('stories.index') }}">← Torna alle storie</a>
            <a class="btn secondary" href="{{ route('stories.edit', $story) }}">Modifica dettagli storia</a>
        </div>
    </div>

    @if (!empty($warnings))
        <div class="warning-box">
            <h3>Controlli automatici</h3>
            <p>Prima di pubblicare o testare la storia, controlla questi punti:</p>

            <ul>
                @foreach ($warnings as $warning)
                    <li>{{ $warning }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="quick-guide">
        <h2>Come costruire questa storia</h2>
        <ol>
            <li>Crea i nodi: ogni nodo rappresenta una scena.</li>
            <li>Crea i token: sono oggetti, ricompense o elementi narrativi.</li>
            <li>Dentro ogni nodo aggiungi le scelte e collegale al nodo successivo.</li>
            <li>Usa la pagina del nodo per controllare il flusso della storia.</li>
        </ol>
    </div>

    <div class="section-grid">

        <section class="section-card">
            <div class="section-header">
                <div>
                    <h2>Nodi della storia</h2>
                    <p class="section-help">
                        Ogni nodo è una scena. Collegali tra loro tramite le scelte.
                    </p>
                </div>

                <a class="btn" href="{{ route('stories.nodes.create', $story) }}">+ Nuovo nodo</a>
            </div>

            @forelse ($story->nodes as $node)
                <article class="node-card">
                    <h3>
                        {{ $node->title ?? 'Nodo senza titolo' }}

                        @if ($node->is_start)
                            <span class="badge start">START</span>
                        @endif
                    </h3>

                    <p>{{ Str::limit($node->text, 130) }}</p>

                    <div class="node-meta">
                        <span class="badge">Scelte: {{ $node->choices->count() }}</span>
                        <span class="badge">ID nodo: {{ $node->id }}</span>
                    </div>

                    <div class="actions">
                        <a class="btn" href="{{ route('stories.nodes.show', [$story, $node]) }}">Apri nodo</a>
                        <a class="btn light" href="{{ route('stories.nodes.edit', [$story, $node]) }}">Modifica</a>

                        <form class="inline-form" action="{{ route('stories.nodes.destroy', [$story, $node]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger"
                                onclick="return confirm('Vuoi davvero eliminare questo nodo? Verranno eliminate anche le sue scelte.')">
                                Elimina
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <strong>Nessun nodo ancora creato.</strong>
                    <p>Inizia creando il primo nodo della storia. Puoi marcarlo come nodo iniziale.</p>
                </div>
            @endforelse
        </section>

        <aside class="section-card">
            <div class="section-header">
                <div>
                    <h2>Token</h2>
                    <p class="section-help">
                        Oggetti o ricompense che il giocatore può ottenere scegliendo certe risposte.
                    </p>
                </div>
            </div>

            <a class="btn" href="{{ route('stories.tokens.create', $story) }}">+ Nuovo token</a>

            <br><br>

            @forelse ($story->tokens as $token)
                <article class="token-card">
                    @if ($token->image)
                        <img src="{{ asset('storage/' . $token->image) }}" width="64">
                    @endif

                    <h3>{{ $token->name }}</h3>

                    <div class="actions">
                        <a class="btn light" href="{{ route('stories.tokens.edit', [$story, $token]) }}">Modifica</a>

                        <form class="inline-form" action="{{ route('stories.tokens.destroy', [$story, $token]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger"
                                onclick="return confirm('Vuoi davvero eliminare questo token? Verrà rimosso anche dalle scelte che lo assegnano.')">
                                Elimina
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <strong>Nessun token.</strong>
                    <p>Crea token solo se vuoi dare oggetti o ricompense al giocatore.</p>
                </div>
            @endforelse
        </aside>

    </div>

@endsection