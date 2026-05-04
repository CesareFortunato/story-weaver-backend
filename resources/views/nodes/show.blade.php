<h1>{{ $node->title ?? 'Nodo senza titolo' }}</h1>

@if ($node->is_start)
    <p>⭐ Nodo iniziale</p>
@endif

<p>{{ $node->text }}</p>

@if ($node->image)
    <img src="{{ asset('storage/' . $node->image) }}" width="300">
@endif

<hr>

<h2>Scelte</h2>

<a href="{{ route('stories.nodes.choices.create', [$story, $node]) }}">+ Aggiungi scelta</a>

@if ($node->choices->isEmpty())
    <p>Questo nodo non ha ancora scelte.</p>
@else
    <ul>
        @foreach ($node->choices as $choice)
            <li>
                <strong>{{ $choice->text }}</strong>

                @if ($choice->nextNode)
                    → {{ $choice->nextNode->title ?? 'Nodo senza titolo' }}
                @else
                    → Nessuna destinazione
                @endif

                <br>
                Ordine: {{ $choice->order }}

                @if ($choice->tokens->isNotEmpty())
                    <br>
                    Token dati:
                    @foreach ($choice->tokens as $token)
                        <span>
                            @if ($token->image)
                                <img src="{{ asset('storage/' . $token->image) }}" width="24">
                            @endif
                            {{ $token->name }}
                        </span>
                    @endforeach
                @endif

                <br>

                <a href="{{ route('stories.nodes.choices.edit', [$story, $node, $choice]) }}">Edit</a>

                <form action="{{ route('stories.nodes.choices.destroy', [$story, $node, $choice]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif

<hr>

<a href="{{ route('stories.nodes.edit', [$story, $node]) }}">Modifica nodo</a>
<a href="{{ route('stories.show', $story) }}">← Torna alla story</a>