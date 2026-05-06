@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Crea nuova Story</h1>
        <p class="page-subtitle">
            Una story è il contenitore principale della tua avventura: conterrà nodi, scelte e token.
        </p>
    </div>

    <section class="section-card">
        <form method="POST" action="{{ route('stories.store') }}">
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

            <div class="actions">
                <button class="btn">Salva Story</button>
                <a class="btn light" href="{{ route('stories.index') }}">Annulla</a>
            </div>
        </form>
    </section>

@endsection