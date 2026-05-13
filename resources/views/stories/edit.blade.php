@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Modifica Story</h1>

        <p class="page-subtitle">
            Aggiorna titolo e descrizione della storia.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form modifica storia --}}
        <form method="POST" action="{{ route('stories.update', $story) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Campo titolo --}}
            <div class="form-group">
                <label>Titolo</label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $story->title) }}"
                    required
                >

                {{-- Mostra eventuali errori di validazione --}}
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo descrizione --}}
            <div class="form-group">
                <label>Descrizione</label>

                <textarea name="description" rows="5">{{ old('description', $story->description) }}</textarea>

                {{-- Mostra eventuali errori di validazione --}}
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gestione audio ambientale --}}
            <div class="form-group">
                <label>Audio ambientale</label>

                {{-- Se esiste già un file audio, mostra il link per ascoltarlo --}}
                @if ($story->ambient_audio)
                    <p class="form-help">
                        Audio attuale:

                        <a href="{{ asset('storage/' . $story->ambient_audio) }}" target="_blank">
                            Ascolta file
                        </a>
                    </p>
                @endif

                {{-- Upload nuovo file audio --}}
                <input
                    type="file"
                    name="ambient_audio"
                    accept="audio/mpeg,audio/wav,audio/ogg"
                >

                {{-- Messaggio informativo --}}
                <p class="form-help">
                    Carica un nuovo file solo se vuoi sostituire quello attuale.
                </p>

                {{-- Mostra eventuali errori di validazione --}}
                @error('ambient_audio')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pulsanti finali --}}
            <div class="actions">

                {{-- Conferma aggiornamento --}}
                <button class="btn">
                    Aggiorna Story
                </button>

                {{-- Ritorna al dettaglio della storia --}}
                <a class="btn light" href="{{ route('stories.show', $story) }}">
                    Annulla
                </a>
            </div>
        </form>
    </section>

@endsection