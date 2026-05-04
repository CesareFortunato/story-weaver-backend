<h1>Crea Token per: {{ $story->title }}</h1>

<form method="POST" action="{{ route('stories.tokens.store', $story) }}" enctype="multipart/form-data">
    @csrf

    <input type="text" name="name" placeholder="Nome token" required>

    <br><br>

    <input type="file" name="image">

    <br><br>

    <button>Salva Token</button>
</form>

<a href="{{ route('stories.show', $story) }}">← Torna alla story</a>