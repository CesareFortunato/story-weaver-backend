@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Crea nuova Story</h1>
        <p class="page-subtitle">
            Una story è il contenitore principale della tua avventura: conterrà nodi, scelte e token.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Titolo</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>Descrizione</label>
                <textarea name="description" rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>Audio ambientale</label>
                <input type="file" name="ambient_audio" accept="audio/mpeg,audio/wav,audio/ogg">

                <p class="form-help">
                    Carica un loop ambientale per questa storia. Formati supportati: MP3, WAV, OGG.
                </p>

                @error('ambient_audio')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="actions">
                <button class="btn">Salva Story</button>
                <a class="btn light" href="{{ route('stories.index') }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection