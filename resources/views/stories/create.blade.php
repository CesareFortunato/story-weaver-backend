<h1>Create Story</h1>

<form method="POST" action="{{ route('stories.store') }}">
    @csrf

    <input type="text" name="title" placeholder="Title">

    <textarea name="description" placeholder="Description"></textarea>

    <button>Save</button>
</form>