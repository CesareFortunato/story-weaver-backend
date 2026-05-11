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