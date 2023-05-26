<div class="flex pt-8 pb-2 font-semibold border-b border-black text-lg">
    Assets
</div>
<div class="flex">
    <ul class="pt-3 w-full">
        <li class="flex content-center items-center text-center p-5 mb-4 bg-gray-400 rounded">
            <div class="pb-2 w-4/12 border-b border-black w-full ">Saved path</div>
            <div class="pb-2 w-2/12 border-b border-black w-full ">File Name</div>
            <div class="pb-2 w-2/12 border-b border-black w-full">Duration</div>
            <div class="pb-2 w-2/12 border-b border-black w-full">Resolution</div>
            <div class="pb-2 w-2/12 border-b border-black w-full">Actions</div>
        </li>

        @forelse($assets->sortByDesc('height') as $asset)
            <li class="flex content-center text-sm items-center text-center p-2 mb-4 bg-gray-200 rounded">
                <div class="w-4/12 w-full ">
                    <div class="whitespace-normal">{{ $asset->path }}</div>
                </div>
                <div class="w-2/12 w-full "> {{ $asset->original_file_name }}</div>
                <div class="w-2/12 w-full"> {{ $asset->durationToHours() }}</div>
                <div class="w-2/12 w-full"> {{ $asset->width }} x {{ $asset->height }}</div>
                <div class="w-2/12 w-full flex items-center align-items-center space-x-1">
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
