@extends('layouts.admin')

@section('content')

    {{-- Header della pagina --}}
    <div class="page-header">
        <h1>Crea nuovo nodo</h1>

        <p class="page-subtitle">
            Un nodo rappresenta una scena della storia. Può avere testo, immagine e scelte.
        </p>
    </div>

    {{-- Card contenente il form --}}
    <section class="section-card">

        {{-- Form creazione nuovo nodo --}}
        <form method="POST" action="{{ route('stories.nodes.store', $story) }}" enctype="multipart/form-data">
            @csrf

            {{-- Campo titolo nodo --}}
            <div class="form-group">
                <label>Titolo nodo</label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Es. Ingresso della caverna"
                >

                {{-- Mostra eventuali errori di validazione --}}
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo testo della scena --}}
            <div class="form-group">
                <label>Testo scena</label>

                <textarea name="text" rows="6" required>{{ old('text') }}</textarea>

                {{-- Mostra eventuali errori di validazione --}}
                @error('text')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload immagine del nodo --}}
            <div class="form-group">
                <label>Immagine di sfondo</label>

                <input type="file" name="image">

                {{-- Mostra eventuali errori di validazione --}}
                @error('image')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Checkbox per impostare il nodo come iniziale --}}
            <div class="form-group">
                <label>

                    <input
                        type="checkbox"
                        name="is_start"
                        value="1"
                        @checked(old('is_start'))
                    >

                    Questo è il nodo iniziale della storia
                </label>
            </div>

            {{-- Pulsanti finali --}}
            <div class="actions">

                {{-- Salvataggio nodo --}}
                <button class="btn">
                    Salva nodo
                </button>

                {{-- Ritorna al dettaglio della storia --}}
                <a class="btn light" href="{{ route('stories.show', $story) }}">
                    Annulla
                </a>
            </div>
        </form>
    </section>

@endsection