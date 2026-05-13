@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Crea più token</h1>

        <p class="page-subtitle">
            Crea rapidamente più token placeholder.
            Potrai modificarli successivamente.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form per la creazione multipla dei token --}}
        <form method="POST" action="{{ route('stories.tokens.bulk-store', $story) }}">
            @csrf

            {{-- Campo numerico per scegliere quanti token creare --}}
            <div class="form-group">
                <label>Numero di token</label>

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

                {{-- Conferma creazione token --}}
                <button class="btn">
                    Crea token
                </button>

                {{-- Ritorna al dettaglio della storia --}}
                <a class="btn light" href="{{ route('stories.show', $story) }}">
                    Annulla
                </a>
            </div>

        </form>

    </section>

@endsection