<div class="flex border-b border-black pt-8 pb-2 text-lg font-semibold">
    Assets
</div>
<div class="flex">
    <ul class="w-full pt-3">
        <li class="mb-4 flex content-center items-center rounded bg-gray-400 p-5 text-center">
            <div class="w-4/12 w-full border-b border-black pb-2">Saved path</div>
            <div class="w-2/12 w-full border-b border-black pb-2">File Name</div>
            <div class="w-2/12 w-full border-b border-black pb-2">Duration</div>
            <div class="w-2/12 w-full border-b border-black pb-2">Resolution</div>
            <div class="w-2/12 w-full border-b border-black pb-2">Actions</div>
        </li>

        @forelse($assets->sortByDesc('height') as $asset)
            <li class="mb-4 flex content-center items-center rounded bg-gray-200 p-2 text-center text-sm">
                <div class="w-4/12 w-full">
                    <div class="whitespace-normal">{{ $asset->path }}</div>
                </div>
                <div class="w-2/12 w-full"> {{ $asset->original_file_name }}</div>
                <div class="w-2/12 w-full"> {{ $asset->durationToHours() }}</div>
                <div class="w-2/12 w-full"> {{ $asset->width }} x {{ $asset->height }}</div>
                <div class="flex w-2/12 w-full items-center align-items-center space-x-1">
                    <x-form.button :link="route('assets.download',$asset)" type="submit" text="Download"/>
                    <form method="POST"
                          action="{{$asset->path() }}"
                    >
                        @csrf
                        @method('DELETE')
                        <x-form.button :link="$link=false" type="delete" text="Delete" color="red"/>
                    </form>
                </div>
            </li>
        @empty
            <div class="flex text-center">
                <div class="text-lg">
                    No assets
                </div>
            </div>
        @endforelse
    </ul>
</div>
