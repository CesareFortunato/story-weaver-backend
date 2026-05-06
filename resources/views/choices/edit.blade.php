@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Modifica scelta</h1>
        <p class="page-subtitle">
            Aggiorna testo, destinazione e token assegnati da questa scelta.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.nodes.choices.update', [$story, $node, $choice]) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Testo scelta</label>
                <input type="text" name="text" value="{{ old('text', $choice->text) }}" required>
                @error('text') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Nodo di destinazione</label>
                <select name="next_node_id">
                    <option value="">-- Nessuna destinazione --</option>
                    @foreach ($nodes as $targetNode)
                        <option value="{{ $targetNode->id }}" @selected(old('next_node_id', $choice->next_node_id) == $targetNode->id)>
                            {{ $targetNode->title ?? 'Nodo senza titolo' }}
                        </option>
                    @endforeach
                </select>
                @error('next_node_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Ordine visualizzazione</label>
                <input type="number" name="order" value="{{ old('order', $choice->order) }}" min="0">
                @error('order') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Token assegnati</label>

                @forelse ($tokens as $token)
                    <label style="display:block; margin-bottom:8px;">
                        <input type="checkbox" name="tokens[]" value="{{ $token->id }}" @checked(collect(old('tokens', $choice->tokens->pluck('id')->toArray()))->contains($token->id))>
                        {{ $token->name }}
                    </label>
                @empty
                    <p class="section-help">Nessun token creato per questa storia.</p>
                @endforelse
            </div>

            <div class="actions">
                <button class="btn">Aggiorna scelta</button>
                <a class="btn light" href="{{ route('stories.nodes.show', [$story, $node]) }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection