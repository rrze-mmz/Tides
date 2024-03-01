<div class="flex items-center py-4 space-x-4 ">
    <a href="{{ route('series.clips.create', $series) }}">
        <x-button class="bg-blue-600 hover:bg-blue-700">
            Add new clip
        </x-button>
    </a>
    <a href="{{ route('frontend.series.show', $series) }}">
        <x-button class="bg-blue-600 hover:bg-blue-700">
            Go to public page
        </x-button>
    </a>
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
    @can('update-series', $series)
        <x-modals.delete :route="route('series.destroy', $series)">
            <x-slot:title>
                {{__('series.backend.delete.modal title',['series_title'=>$series->title])}}
            </x-slot:title>
            <x-slot:body>
                {{__('series.backend.delete.modal body')}}
            </x-slot:body>
        </x-modals.delete>
    @endcan
</div>
