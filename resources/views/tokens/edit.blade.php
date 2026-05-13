@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Modifica token</h1>

        <p class="page-subtitle">
            Aggiorna nome o immagine del token.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form modifica token --}}
        <form method="POST" action="{{ route('stories.tokens.update', [$story, $token]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Campo nome token --}}
            <div class="form-group">
                <label>Nome token</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $token->name) }}"
                    required
                >

                {{-- Mostra eventuali errori di validazione --}}
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo descrizione token --}}
            <div class="form-group">
                <label>Descrizione token</label>

                <textarea
                    name="description"
                    rows="4"
                >{{ old('description', $token->description) }}</textarea>

                {{-- Mostra eventuali errori di validazione --}}
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Anteprima immagine attuale del token --}}
            @if ($token->image)
                <div class="form-group">
                    <label>Immagine attuale</label>

                    <img
                        class="preview-image"
                        src="{{ asset('storage/' . $token->image) }}"
                    >
                </div>
            @endif

            {{-- Upload nuova immagine --}}
            <div class="form-group">
                <label>Nuova immagine</label>

                <input type="file" name="image">

                {{-- Mostra eventuali errori di validazione --}}
                @error('image')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pulsanti finali --}}
            <div class="actions">

                {{-- Conferma aggiornamento --}}
                <button class="btn">
                    Aggiorna token
                </button>

                {{-- Ritorna al dettaglio della storia --}}
                <a class="btn light" href="{{ route('stories.show', $story) }}">
                    Annulla
                </a>
            </div>
        </form>
    </section>

@endsection