<h1>{{ $node->title ?? 'Nodo senza titolo' }}</h1>

@if ($node->is_start)
    <p style="color: green;">⭐ Nodo iniziale</p>
@endif

<p>{{ $node->text }}</p>

{{-- IMMAGINE --}}
@if ($node->image)
    <img src="{{ asset('storage/' . $node->image) }}" width="300">
@endif

<hr>

{{-- FLOW PREVIEW --}}
<h2>Flow</h2>

<h3>⬅️ Arriva da:</h3>

@if ($incomingChoices->isEmpty())
    <p>Nessun nodo porta qui</p>
@else
    <ul>
        @foreach ($incomingChoices as $choice)
            <li>
                {{ $choice->node->title ?? 'Nodo senza titolo' }}
                → "{{ $choice->text }}"
            </li>
        @endforeach
    </ul>
@endif

<h3>➡️ Va a:</h3>

@if ($node->choices->isEmpty())
    <p>Nessuna uscita da questo nodo</p>
@else
    <ul>
        @foreach ($node->choices as $choice)
            <li>
                "{{ $choice->text }}" →

                @if ($choice->nextNode)
                    {{ $choice->nextNode->title ?? 'Nodo senza titolo' }}
                @else
                    <span style="color: red;">Nessuna destinazione</span>
                @endif
            </li>
        @endforeach
    </ul>
@endif

<hr>

{{-- SCELTE --}}
<h2>Scelte</h2>

<a href="{{ route('stories.nodes.choices.create', [$story, $node]) }}">
    + Aggiungi scelta
</a>

@if ($node->choices->isEmpty())
    <p>Questo nodo non ha ancora scelte.</p>
@else
    <ul>
        @foreach ($node->choices as $choice)
            <li style="margin-bottom: 15px;">

                <strong>{{ $choice->text }}</strong>

                <br>

                @if ($choice->nextNode)
                    → {{ $choice->nextNode->title ?? 'Nodo senza titolo' }}
                @else
                    → <span style="color: red;">Nessuna destinazione</span>
                @endif

                <br>
                Ordine: {{ $choice->order }}

                {{-- TOKEN --}}
                @if ($choice->tokens->isNotEmpty())
                    <br>
                    Token dati:
                    @foreach ($choice->tokens as $token)
                        <span style="margin-right: 10px;">
                            @if ($token->image)
                                <img src="{{ asset('storage/' . $token->image) }}" width="24">
                            @endif
                            {{ $token->name }}
                        </span>
                    @endforeach
                @endif

                <br><br>

                <a href="{{ route('stories.nodes.choices.edit', [$story, $node, $choice]) }}">
                    Edit
                </a>

                <form action="{{ route('stories.nodes.choices.destroy', [$story, $node, $choice]) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button>Delete</button>
                </form>

            </li>
        @endforeach
    </ul>
@endif

<hr>

{{-- AZIONI --}}
<h3>Azioni</h3>

<a href="{{ route('stories.nodes.edit', [$story, $node]) }}">
    Modifica nodo
</a>

<br><br>

<a href="{{ route('stories.show', $story) }}">
    ← Torna alla story
</a>

<br><br>

{{-- KILLER FEATURE --}}
<a href="/play/{{ $node->id }}" target="_blank">
    🎮 Gioca da questo nodo
</a>