@extends('layouts.admin')

@section('content')

{{-- Header della pagina --}}
<div class="page-header">
    <h1>Crea più nodi</h1>

    <p class="page-subtitle">
        Crea rapidamente fino a 20 nodi vuoti per strutturare la storia.
        Potrai modificarli uno alla volta subito dopo.
    </p>
</div>

{{-- Card contenente il form --}}
<section class="section-card">

    {{-- Form per la creazione multipla dei nodi --}}
    <form method="POST" action="{{ route('stories.nodes.bulk-store', $story) }}">
        @csrf

        {{-- Campo numerico per scegliere quanti nodi creare --}}
        <div class="form-group">
            <label>Numero di nodi da creare</label>

            <input
                type="number"
                name="amount"
                value="{{ old('amount', 3) }}"
                min="1"
                max="20"
                required
            >

            {{-- Mostra eventuali errori di validazione --}}
            @error('amount')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Pulsanti finali --}}
        <div class="actions">

            {{-- Conferma creazione --}}
            <button class="btn">
                Crea nodi
            </button>

            {{-- Torna al dettaglio della storia --}}
            <a class="btn light" href="{{ route('stories.show', $story) }}">
                Annulla
            </a>
        </div>
    </form>
</section>

@endsection