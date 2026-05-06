<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>StoryWeaver Admin</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <div class="admin-layout">

        {{-- SIDEBAR --}}
        <aside class="sidebar">

            <div>
                <h1 class="logo">StoryWeaver</h1>

                <p class="sidebar-subtitle">
                    Narrative Builder
                </p>

                <nav class="sidebar-nav">



                    <a href="{{ route('stories.index') }}">
                        📚 Stories
                    </a>

                    <a href="{{ route('stories.create') }}">
                        ➕ Nuova Story
                    </a>

                </nav>
            </div>

            {{-- FOOTER --}}
            <div class="sidebar-footer">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="btn secondary">
                        Logout
                    </button>
                </form>

            </div>

        </aside>

        {{-- CONTENT --}}
        <main class="content">
            @yield('content')
        </main>

    </div>

</body>

</html>