<h1>Modifica Token</h1>

<form method="POST" action="{{ route('stories.tokens.update', [$story, $token]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $token->name }}" required>

    <br><br>

    @if ($token->image)
        <img src="{{ asset('storage/' . $token->image) }}" width="120">
        <br><br>
    @endif

    <input type="file" name="image">

    <br><br>

    <button>Aggiorna Token</button>
</form>

<a href="{{ route('stories.show', $story) }}">← Torna alla story</a>