<div
        class="mx-4 h-full w-full rounded border bg-white px-4 py-4 dark:bg-gray-800  dark:text-white dark:border-blue-800
    font-normal"
>
    <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 py-4 pl-4 text-xl dark:border-blue-800">

        Belongs to: {{ $clip->series->title  }}
    </h2>
    <div class="flex flex-col space-y-4">
        <div>
            <a href="{{route('series.edit', $clip->series)}}">
                <x-button type="button"
                          class="bg-green-600 hover:bg-green-700 w-full justify-center"
                >
                    Series {{__('common.actions.edit')}}
                </x-button>
            </a>
        </div>

        <div>
            <x-modals.delete
                    :btn_text="'Remove Series'"
                    :route="route('series.clips.remove', $clip)"
                    class="w-full justify-center mt-2"
            >
                <x-slot:title>
                    {{ __('clip.backend.delete series connection.modal title', ['series_title' => $clip->series->title]) }}
                </x-slot:title>
                <x-slot:body>
                    {{ __('clip.backend.delete series connection.modal body') }}
                </x-slot:body>
            </x-modals.delete>
        </div>

    </div>

</div>
