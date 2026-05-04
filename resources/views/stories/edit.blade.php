<h1>Edit Story</h1>

<form method="POST" action="{{ route('stories.update', $story) }}">
    @csrf
    @method('PUT')

    <input type="text" name="title" value="{{ $story->title }}">

    <textarea name="description">{{ $story->description }}</textarea>

    <button>Update</button>
</form>