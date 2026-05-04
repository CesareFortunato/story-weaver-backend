<h1>Aggiungi scelta</h1>

<p>Nodo: <strong>{{ $node->title ?? 'Nodo senza titolo' }}</strong></p>

<form method="POST" action="{{ route('stories.nodes.choices.store', [$story, $node]) }}">
    @csrf

    <label>Testo scelta</label>
    <br>
    <input type="text" name="text" required>

    <br><br>

    <label>Nodo di destinazione</label>
    <br>
    <select name="next_node_id">
        <option value="">-- Nessuna destinazione --</option>
        @foreach ($nodes as $targetNode)
            <option value="{{ $targetNode->id }}">
                {{ $targetNode->title ?? 'Nodo senza titolo' }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Ordine</label>
    <br>
    <input type="number" name="order" value="0" min="0">

    <br><br>

    <label>Token assegnati</label>
    <br>

    @forelse ($tokens as $token)
        <label>
            <input type="checkbox" name="tokens[]" value="{{ $token->id }}">
            {{ $token->name }}
        </label>
        <br>
    @empty
        <p>Nessun token creato per questa storia.</p>
    @endforelse

    <br>

    <button>Salva scelta</button>
</form>

<a href="{{ route('stories.nodes.show', [$story, $node]) }}">← Torna al nodo</a>