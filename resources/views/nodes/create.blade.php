@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Crea nuovo nodo</h1>
        <p class="page-subtitle">
            Un nodo rappresenta una scena della storia. Può avere testo, immagine e scelte.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.nodes.store', $story) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Titolo nodo</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Es. Ingresso della caverna">
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Testo scena</label>
                <textarea name="text" rows="6" required>{{ old('text') }}</textarea>
                @error('text') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Immagine di sfondo</label>
                <input type="file" name="image">
                @error('image') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_start" value="1" @checked(old('is_start'))>
                    Questo è il nodo iniziale della storia
                </label>
            </div>

            <div class="actions">
                <button class="btn">Salva nodo</button>
                <a class="btn light" href="{{ route('stories.show', $story) }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection