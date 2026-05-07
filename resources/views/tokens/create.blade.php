@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Crea nuovo token</h1>
        <p class="page-subtitle">
            I token sono oggetti, ricompense o elementi narrativi che il giocatore può ottenere tramite le scelte.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.tokens.store', $story) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nome token</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Es. Chiave dorata">
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Descrizione token</label>
                <textarea name="description" rows="4"
                    placeholder="Es. Una chiave antica macchiata di sangue secco...">{{ old('description') }}</textarea>

                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>Immagine token</label>
                <input type="file" name="image">
                @error('image') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="actions">
                <button class="btn">Salva token</button>
                <a class="btn light" href="{{ route('stories.show', $story) }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection