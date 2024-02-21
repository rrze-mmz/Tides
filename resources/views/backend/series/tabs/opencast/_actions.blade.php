@can('administrate-admin-portal-pages')
    <div class="flex flex-col font-normal py-4">
        <h4 class="mt-4 mb-4 text-green-700  dark:text-green-400">
            <span class="text-xl font-bold">Opencast actions</span>
        </h4>
        <div class="flex space-x-4">
            <form action="{{route('series.opencast.updateEventsTitle', $series)}}"
                  method="POST"
                  class="flex"
            >
                <input hidden readonly type="text" name="opencastSeriesID" value="{{$series->opencast_series_id}}">
                @csrf
                <x-button type="link" class="bg-green-700 hover:bg-green-800">
                    Remove numbers from title
                </x-button>
            </form>

            <x-button class="bg-green-700 hover:bg-green-800">
                Add all planned recordings as clips
            </x-button>
        </div>
    </div>
@endcan
