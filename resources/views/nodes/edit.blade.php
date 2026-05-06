@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Modifica nodo</h1>
        <p class="page-subtitle">
            Aggiorna il contenuto della scena o impostala come nodo iniziale.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.nodes.update', [$story, $node]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Titolo nodo</label>
                <input type="text" name="title" value="{{ old('title', $node->title) }}">
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Testo scena</label>
                <textarea name="text" rows="6" required>{{ old('text', $node->text) }}</textarea>
                @error('text') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            @if ($node->image)
                <div class="form-group">
                    <label>Immagine attuale</label>
                    <img class="preview-image" src="{{ asset('storage/' . $node->image) }}">
                </div>
            @endif

            <div class="form-group">
                <label>Nuova immagine</label>
                <input type="file" name="image">
                @error('image') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_start" value="1" @checked(old('is_start', $node->is_start))>
                    Questo è il nodo iniziale della storia
                </label>
            </div>

            <div class="actions">
                <button class="btn">Aggiorna nodo</button>
                <a class="btn light" href="{{ route('stories.nodes.show', [$story, $node]) }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection