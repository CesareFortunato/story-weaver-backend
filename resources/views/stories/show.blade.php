<h1>{{ $story->title }}</h1>

<p>{{ $story->description }}</p>

<a href="{{ route('stories.index') }}">← Torna alle stories</a>
<a href="{{ route('stories.edit', $story) }}">Modifica story</a>

<hr>

<h2>Nodi</h2>

<a href="{{ route('stories.nodes.create', $story) }}">+ Crea Nodo</a>

@if ($story->nodes->isEmpty())
    <p>Nessun nodo creato.</p>
@else
    <ul>
        @foreach ($story->nodes as $node)
            <li>
                <strong>
                    {{ $node->title ?? 'Nodo senza titolo' }}
                </strong>

                @if ($node->is_start)
                    ⭐ Start
                @endif

                <br>

                {{ Str::limit($node->text, 80) }}

                <br>

                Scelte: {{ $node->choices->count() }}

                <br>

                <a href="{{ route('stories.nodes.show', [$story, $node]) }}">View</a>
                <a href="{{ route('stories.nodes.edit', [$story, $node]) }}">Edit</a>

                <form action="{{ route('stories.nodes.destroy', [$story, $node]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif

<hr>

<h2>Token</h2>

<a href="{{ route('stories.tokens.create', $story) }}">+ Crea Token</a>

@if ($story->tokens->isEmpty())
    <p>Nessun token creato.</p>
@else
    <ul>
        @foreach ($story->tokens as $token)
            <li>
                @if ($token->image)
                    <img src="{{ asset('storage/' . $token->image) }}" width="40">
                @endif

                {{ $token->name }}

                <a href="{{ route('stories.tokens.edit', [$story, $token]) }}">Edit</a>

                <form action="{{ route('stories.tokens.destroy', [$story, $token]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif