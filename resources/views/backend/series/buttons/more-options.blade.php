<div class="flex items-center pt-3 space-x-2">
    <a href="{{ route('series.clips.changeEpisode', $series) }}">
        <x-button class="bg-green-600 hover:bg-green-700">
            Reorder clips
        </x-button>
    </a>
    <a href="{{ route('series.chapters.index', $series) }}">
        <x-button class="bg-green-600 hover:bg-green-700">
            Manage chapters
        </x-button>
    </a>
</div>
