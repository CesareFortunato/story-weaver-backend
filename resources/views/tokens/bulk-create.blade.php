@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Crea più token</h1>

        <p class="page-subtitle">
            Crea rapidamente più token placeholder.
            Potrai modificarli successivamente.
        </p>
    </div>

    <section class="section-card">

        <form method="POST" action="{{ route('stories.tokens.bulk-store', $story) }}">
            @csrf

            <div class="form-group">
                <label>Numero di token</label>

                <input type="number" name="amount" value="{{ old('amount', 3) }}" min="1" max="20" required>

                @error('amount')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="actions">
                <button class="btn">
                    Crea token
                </button>

                <a class="btn light" href="{{ route('stories.show', $story) }}">
                    Annulla
                </a>
            </div>

        </form>

    </section>

@endsection