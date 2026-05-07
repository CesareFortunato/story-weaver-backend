@extends('layouts.admin')

@section('content')

<div class="page-header">
    <h1>Crea più nodi</h1>
    <p class="page-subtitle">
        Crea rapidamente fino a 20 nodi vuoti per strutturare la storia.
        Potrai modificarli uno alla volta subito dopo.
    </p>
</div>

<section class="section-card">
    <form method="POST" action="{{ route('stories.nodes.bulk-store', $story) }}">
        @csrf

        <div class="form-group">
            <label>Numero di nodi da creare</label>
            <input type="number" name="amount" value="{{ old('amount', 3) }}" min="1" max="20" required>

            @error('amount')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="actions">
            <button class="btn">Crea nodi</button>
            <a class="btn light" href="{{ route('stories.show', $story) }}">Annulla</a>
        </div>
    </form>
</section>

@endsection