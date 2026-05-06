<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>StoryWeaver Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <div class="admin-layout">

        <aside class="sidebar">
            <h2>StoryWeaver</h2>

            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('stories.index') }}">Stories</a>

            <hr>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn secondary">Logout</button>
            </form>
        </aside>

        <main class="content">
            @yield('content')
        </main>

    </div>

</body>

</html>