<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Graph Editor
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $story->title }}
                </p>
            </div>
            <div class="flex gap-3 mt-3 text-sm flex-wrap">

                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded border-2 border-yellow-400 bg-yellow-900"></div>
                    <span class="text-gray-300">Nodo iniziale</span>
                </div>

                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded border-2 border-red-500 bg-red-900"></div>
                    <span class="text-gray-300">Nodo finale</span>
                </div>

                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded border border-slate-600 bg-slate-800"></div>
                    <span class="text-gray-300">Nodo normale</span>
                </div>

            </div>

            <a href="{{ route('stories.show', $story) }}"
                class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
                Torna al dettaglio
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="story-graph-root" data-story-id="{{ $story->id }}"
                data-graph-url="{{ route('stories.graph-data', $story) }}"
                data-position-url-template="{{ url('/nodes') }}/__NODE_ID__/position"
                data-node-url-template="{{ url('/stories/' . $story->id . '/nodes') }}/__NODE_ID__"
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden" style="height: 750px;"></div>
        </div>

        @vite(['resources/js/story-graph.jsx'])
</x-app-layout>