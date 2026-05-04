<h1>Crea Nodo per: {{ $story->title }}</h1>

<form method="POST" action="{{ route('stories.nodes.store', $story) }}" enctype="multipart/form-data">
    @csrf

    <input type="text" name="title" placeholder="Titolo nodo">

    <br><br>

    <textarea name="text" placeholder="Testo scena" required></textarea>

    <br><br>

    <input type="file" name="image">

    <br><br>

    <label>
        <input type="checkbox" name="is_start" value="1">
        Nodo iniziale
    </label>

    <br><br>

    <button>Salva Nodo</button>
</form>

<a href="{{ route('stories.show', $story) }}">← Torna alla story</a>