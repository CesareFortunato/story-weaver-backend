@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Crea più scelte</h1>

        <p class="page-subtitle">
            Nodo di partenza:
            <strong>{{ $node->title ?? 'Nodo senza titolo' }}</strong>.
            Crea fino a 20 scelte placeholder, poi potrai modificarle e collegarle ai nodi successivi.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form per creare più scelte contemporaneamente --}}
        <form method="POST" action="{{ route('stories.nodes.choices.bulk-store', [$story, $node]) }}">
            @csrf

            {{-- Campo numerico per scegliere quante scelte creare --}}
            <div class="form-group">
                <label>Numero di scelte da creare</label>

                <input type="number" name="amount" value="{{ old('amount', 3) }}" min="1" max="20" required>

                {{-- Mostra eventuali errori di validazione sul campo amount --}}
                @error('amount')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pulsanti di conferma o annullamento --}}
            <div class="actions">
                <button class="btn">Crea scelte</button>

                <a class="btn light" href="{{ route('stories.nodes.show', [$story, $node]) }}">
                    Annulla
                </a>
            </div>
        </form>
    </section>

@endsection