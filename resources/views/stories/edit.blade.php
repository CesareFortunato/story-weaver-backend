@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Modifica Story</h1>
        <p class="page-subtitle">
            Aggiorna titolo e descrizione della storia.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.update', $story) }}" enctype="multipart/form-data>
                    @csrf
                    @method('PUT')

                    <div class=" form-group">
            <label>Titolo</label>
            <input type="text" name="title" value="{{ old('title', $story->title) }}" required>
            @error('title')
                <p class="form-error">{{ $message }}</p>
            @enderror
            </div>

            <div class="form-group">
                <label>Descrizione</label>
                <textarea name="description" rows="5">{{ old('description', $story->description) }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>Audio ambientale</label>

                @if ($story->ambient_audio)
                    <p class="form-help">
                        Audio attuale:
                        <a href="{{ asset('storage/' . $story->ambient_audio) }}" target="_blank">
                            Ascolta file
                        </a>
                    </p>
                @endif

                <input type="file" name="ambient_audio" accept="audio/mpeg,audio/wav,audio/ogg">

                <p class="form-help">
                    Carica un nuovo file solo se vuoi sostituire quello attuale.
                </p>

                @error('ambient_audio')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="actions">
                <button class="btn">Aggiorna Story</button>
                <a class="btn light" href="{{ route('stories.show', $story) }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection