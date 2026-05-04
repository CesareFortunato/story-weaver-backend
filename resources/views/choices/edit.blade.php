<h1>Modifica scelta</h1>

<p>Nodo: <strong>{{ $node->title ?? 'Nodo senza titolo' }}</strong></p>

<form method="POST" action="{{ route('stories.nodes.choices.update', [$story, $node, $choice]) }}">
    @csrf
    @method('PUT')

    <label>Testo scelta</label>
    <br>
    <input type="text" name="text" value="{{ $choice->text }}" required>

    <br><br>

    <label>Nodo di destinazione</label>
    <br>
    <select name="next_node_id">
        <option value="">-- Nessuna destinazione --</option>
        @foreach ($nodes as $targetNode)
            <option value="{{ $targetNode->id }}" @selected($choice->next_node_id === $targetNode->id)>
                {{ $targetNode->title ?? 'Nodo senza titolo' }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Ordine</label>
    <br>
    <input type="number" name="order" value="{{ $choice->order }}" min="0">

    <br><br>

    <label>Token assegnati</label>
    <br>

    @forelse ($tokens as $token)
        <label>
            <input type="checkbox" name="tokens[]" value="{{ $token->id }}" @checked($choice->tokens->contains($token->id))>
            {{ $token->name }}
        </label>
        <br>
    @empty
        <p>Nessun token creato per questa storia.</p>
    @endforelse

    <br>

    <button>Aggiorna scelta</button>
</form>

<a href="{{ route('stories.nodes.show', [$story, $node]) }}">← Torna al nodo</a>