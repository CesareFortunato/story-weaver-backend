@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Crea nuova Story</h1>

        <p class="page-subtitle">
            Una story è il contenitore principale della tua avventura: conterrà nodi, scelte e token.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form creazione nuova story --}}
        <form method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Campo titolo della storia --}}
            <div class="form-group">
                <label>Titolo</label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
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

                <textarea name="description" rows="5">{{ old('description') }}</textarea>

                {{-- Mostra eventuali errori di validazione --}}
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload audio ambientale --}}
            <div class="form-group">
                <label>Audio ambientale</label>

                <input
                    type="file"
                    name="ambient_audio"
                    accept="audio/mpeg,audio/wav,audio/ogg"
                >

                {{-- Testo informativo sui formati supportati --}}
                <p class="form-help">
                    Carica un loop ambientale per questa storia. Formati supportati: MP3, WAV, OGG.
                </p>

                {{-- Mostra eventuali errori di validazione --}}
                @error('ambient_audio')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pulsanti finali --}}
            <div class="actions">

                {{-- Salvataggio della storia --}}
                <button class="btn">
                    Salva Story
                </button>

                {{-- Ritorna alla lista delle stories --}}
                <a class="btn light" href="{{ route('stories.index') }}">
                    Annulla
                </a>
            </div>
        </form>
    </section>

@endsection