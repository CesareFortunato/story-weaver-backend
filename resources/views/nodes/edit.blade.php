<h1>Modifica Nodo</h1>

<form method="POST" action="{{ route('stories.nodes.update', [$story, $node]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="text" name="title" value="{{ $node->title }}" placeholder="Titolo nodo">

    <br><br>

    <textarea name="text" required>{{ $node->text }}</textarea>

    <br><br>

    @if ($node->image)
        <img src="{{ asset('storage/' . $node->image) }}" width="200">
        <br><br>
    @endif

    <input type="file" name="image">

    <br><br>

    <label>
        <input type="checkbox" name="is_start" value="1" @checked($node->is_start)>
        Nodo iniziale
    </label>

    <br><br>

    <button>Aggiorna Nodo</button>
</form>

<a href="{{ route('stories.nodes.show', [$story, $node]) }}">← Torna al nodo</a>