<div class="flex items-center pt-3 space-x-4 ">
    <a href="{{ route('frontend.series.show', $series) }}">
        <x-button class="bg-blue-600 hover:bg-blue-700">
            Go to public page
        </x-button>
    </a>

    <a href="{{ route('series.clips.create', $series) }}">
        <x-button class="bg-blue-600 hover:bg-blue-700">
            Add new clip
        </x-button>
    </a>

    @can('update-series', $series)
        <form action="{{$series->adminPath()}}"
              method="POST">
            @csrf
            @method('DELETE')
            <x-button class="bg-red-600 hover:bg-red-700">
                {{__('common.actions.delete') }}
            </x-button>
        </form>
    @endcan
</div>
