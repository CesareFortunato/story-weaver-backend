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

<a href="#">+ Aggiungi scelta</a>

@if ($node->choices->isEmpty())
    <p>Questo nodo non ha ancora scelte.</p>
@else
    <ul>
        @foreach ($node->choices as $choice)
            <li>
                {{ $choice->text }}

                @if ($choice->nextNode)
                    → {{ $choice->nextNode->title }}
                @else
                    → Nessuna destinazione
                @endif
            </li>
        @endforeach
    </ul>
@endif

<hr>

<a href="{{ route('stories.nodes.edit', [$story, $node]) }}">Modifica nodo</a>
<a href="{{ route('stories.show', $story) }}">← Torna alla story</a>