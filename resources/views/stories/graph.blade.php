<x-app-layout>
    <x-slot name="header">

        {{-- Header della pagina Graph Editor --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Graph Editor
                </h2>

                {{-- Titolo della storia corrente --}}
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $story->title }}
                </p>
            </div>

            {{-- Legenda dei colori dei nodi --}}
            <div class="flex gap-3 mt-3 text-sm flex-wrap">

                {{-- Nodo iniziale --}}
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded border-2 border-yellow-400 bg-yellow-900"></div>
                    <span class="text-gray-300">Nodo iniziale</span>
                </div>

                {{-- Nodo finale --}}
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded border-2 border-red-500 bg-red-900"></div>
                    <span class="text-gray-300">Nodo finale</span>
                </div>

                {{-- Nodo normale --}}
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded border border-slate-600 bg-slate-800"></div>
                    <span class="text-gray-300">Nodo normale</span>
                </div>

            </div>

            {{-- Link per tornare al dettaglio della storia --}}
            <a href="{{ route('stories.show', $story) }}"
                class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
                Torna al dettaglio
            </a>
        </div>
    </x-slot>

    {{-- Contenuto principale della pagina --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{--
                Div in cui viene montata l'app React del grafo.
                I data-attribute passano a React gli URL necessari per:
                - caricare il grafo
                - salvare la posizione dei nodi
                - aprire il dettaglio di un nodo
            --}}
            <div
                id="story-graph-root"
                data-story-id="{{ $story->id }}"
                data-graph-url="{{ route('stories.graph-data', $story) }}"
                data-position-url-template="{{ url('/nodes') }}/__NODE_ID__/position"
                data-node-url-template="{{ url('/stories/' . $story->id . '/nodes') }}/__NODE_ID__"
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden"
                style="height: 750px;"
            ></div>
        </div>

        {{-- Carica il file React/Vite che gestisce il Graph Editor --}}
        @vite(['resources/js/story-graph.jsx'])
</x-app-layout>