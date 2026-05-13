@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Crea nuovo token</h1>

        <p class="page-subtitle">
            I token sono oggetti, ricompense o elementi narrativi che il giocatore può ottenere tramite le scelte.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form creazione nuovo token --}}
        <form method="POST" action="{{ route('stories.tokens.store', $story) }}" enctype="multipart/form-data">
            @csrf

            {{-- Campo nome token --}}
            <div class="form-group">
                <label>Nome token</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Es. Chiave dorata"
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
                    placeholder="Es. Una chiave antica macchiata di sangue secco..."
                >{{ old('description') }}</textarea>

                {{-- Mostra eventuali errori di validazione --}}
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload immagine token --}}
            <div class="form-group">
                <label>Immagine token</label>

                <input type="file" name="image">

                {{-- Mostra eventuali errori di validazione --}}
                @error('image')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pulsanti finali --}}
            <div class="actions">

                {{-- Conferma creazione token --}}
                <button class="btn">
                    Salva token
                </button>

                {{-- Ritorna al dettaglio della storia --}}
                <a class="btn light" href="{{ route('stories.show', $story) }}">
                    Annulla
                </a>
            </div>
        </form>
    </section>

@endsection