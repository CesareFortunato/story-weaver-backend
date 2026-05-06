@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Aggiungi scelta</h1>
        <p class="page-subtitle">
            Nodo di partenza: <strong>{{ $node->title ?? 'Nodo senza titolo' }}</strong>.
            La scelta porta il giocatore verso un altro nodo e può assegnare token.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.nodes.choices.store', [$story, $node]) }}">
            @csrf

            <div class="form-group">
                <label>Testo scelta</label>
                <input type="text" name="text" value="{{ old('text') }}" required placeholder="Es. Apri la porta">
                @error('text') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Nodo di destinazione</label>
                <select name="next_node_id">
                    <option value="">-- Nessuna destinazione --</option>
                    @foreach ($nodes as $targetNode)
                        <option value="{{ $targetNode->id }}" @selected(old('next_node_id') == $targetNode->id)>
                            {{ $targetNode->title ?? 'Nodo senza titolo' }}
                        </option>
                    @endforeach
                </select>
                @error('next_node_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Ordine visualizzazione</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" min="0">
                @error('order') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Token assegnati</label>

                @forelse ($tokens as $token)
                    <label style="display:block; margin-bottom:8px;">
                        <input type="checkbox" name="tokens[]" value="{{ $token->id }}" @checked(collect(old('tokens', []))->contains($token->id))>
                        {{ $token->name }}
                    </label>
                @empty
                    <p class="section-help">Nessun token creato per questa storia.</p>
                @endforelse
            </div>

            <div class="actions">
                <button class="btn">Salva scelta</button>
                <a class="btn light" href="{{ route('stories.nodes.show', [$story, $node]) }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection