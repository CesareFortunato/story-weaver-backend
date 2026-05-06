@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Modifica token</h1>
        <p class="page-subtitle">
            Aggiorna nome o immagine del token.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.tokens.update', [$story, $token]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nome token</label>
                <input type="text" name="name" value="{{ old('name', $token->name) }}" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            @if ($token->image)
                <div class="form-group">
                    <label>Immagine attuale</label>
                    <img class="preview-image" src="{{ asset('storage/' . $token->image) }}">
                </div>
            @endif

            <div class="form-group">
                <label>Nuova immagine</label>
                <input type="file" name="image">
                @error('image') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="actions">
                <button class="btn">Aggiorna token</button>
                <a class="btn light" href="{{ route('stories.show', $story) }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection