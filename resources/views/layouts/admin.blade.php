<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>StoryWeaver Admin</title>

    {{-- Collegamento al file CSS personalizzato dell'area admin --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    {{-- Layout principale dell'area amministrativa --}}
    <div class="admin-layout">

        {{-- Sidebar laterale --}}
        <aside class="sidebar">

            <div>
                {{-- Logo/titolo del pannello admin --}}
                <h1 class="logo">StoryWeaver</h1>

                <p class="sidebar-subtitle">
                    Narrative Builder
                </p>

                {{-- Menu di navigazione principale --}}
                <nav class="sidebar-nav">

                    {{-- Link alla lista delle storie --}}
                    <a href="{{ route('stories.index') }}">
                        📚 Stories
                    </a>

                    {{-- Link al form di creazione nuova storia --}}
                    <a href="{{ route('stories.create') }}">
                        ➕ Nuova Story
                    </a>

                </nav>
            </div>

            {{-- Footer della sidebar --}}
            <div class="sidebar-footer">

                {{-- Form di logout dell'utente autenticato --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="btn secondary">
                        Logout
                    </button>
                </form>

            </div>

        </aside>

        {{-- Area principale in cui vengono inseriti i contenuti delle singole view --}}
        <main class="content">
            @yield('content')
        </main>

    </div>

</body>

</html>