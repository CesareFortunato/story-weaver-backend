@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Modifica nodo</h1>

        <p class="page-subtitle">
            Aggiorna il contenuto della scena o impostala come nodo iniziale.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form modifica nodo --}}
        <form method="POST" action="{{ route('stories.nodes.update', [$story, $node]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Campo titolo nodo --}}
            <div class="form-group">
                <label>Titolo nodo</label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $node->title) }}"
                >

                {{-- Mostra eventuali errori di validazione --}}
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo testo scena --}}
            <div class="form-group">
                <label>Testo scena</label>

                <textarea name="text" rows="6" required>{{ old('text', $node->text) }}</textarea>

                {{-- Mostra eventuali errori di validazione --}}
                @error('text')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Anteprima immagine attuale del nodo --}}
            @if ($node->image)
                <div class="form-group">
                    <label>Immagine attuale</label>

                    <img
                        class="preview-image"
                        src="{{ asset('storage/' . $node->image) }}"
                    >
                </div>
            @endif

            {{-- Upload nuova immagine --}}
            <div class="form-group">
                <label>Nuova immagine</label>

                <input type="file" name="image">

                {{-- Mostra eventuali errori di validazione --}}
                @error('image')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Checkbox per impostare il nodo come nodo iniziale --}}
            <div class="form-group">
                <label>

                    <input
                        type="checkbox"
                        name="is_start"
                        value="1"
                        @checked(old('is_start', $node->is_start))
                    >

                    Questo è il nodo iniziale della storia
                </label>
            </div>

            {{-- Pulsanti finali --}}
            <div class="actions">

                {{-- Conferma modifica --}}
                <button class="btn">
                    Aggiorna nodo
                </button>

                {{-- Ritorna al dettaglio del nodo --}}
                <a
                    class="btn light"
                    href="{{ route('stories.nodes.show', [$story, $node]) }}"
                >
                    Annulla
                </a>
            </div>
        </form>
    </section>

@endsection