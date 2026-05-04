<h1>Stories</h1>

<a href="{{ route('stories.create') }}">+ Nuova Story</a>

<ul>
    @foreach ($stories as $story)
        <li>
            <strong>{{ $story->title }}</strong>

            <a href="{{ route('stories.edit', $story) }}">Edit</a>
            <a href="{{ route('stories.show', $story) }}">View</a>

            <form action="{{ route('stories.destroy', $story) }}" method="POST">
                @csrf
                @method('DELETE')
                <button>Delete</button>
            </form>
            
        </li>
    @endforeach
</ul>