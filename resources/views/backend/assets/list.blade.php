<div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
    Assets
</div>
<div class="flex">
    <ul class="pt-3 w-full">
        <li class="flex content-center items-center p-5 mb-4 text-xl bg-gray-400 rounded">
            <div class="pb-2 w-1/5 border-b border-black">ID</div>
            <div class="pb-2 w-3/5 border-b border-black">Saved path</div>
            <div class="pb-2 w-1/5 border-b border-black">Resolution</div>
            <div class="pb-2 w-1/5 border-b border-black">Actions</div>
        </li>

        @forelse($assets as $asset)
            <li class="flex content-center items-center p-5 mb-4 text-xl bg-gray-200 rounded">
                <div class="w-1/5"> {{ $asset->id }}</div>
                <div class="w-3/5"> {{ $asset->path }}</div>
                <div class="w-1/5"> {{ $asset->width }} x {{ $asset->height }}</div>
                <div class="w-1/5">
                    <form method="POST"
                          action="{{$asset->path() }}"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-red-700 hover:bg-red-500 hover:shadow-lg"> Delete </button>
                    </form>
                </div>
            </li>
        @empty
            No assets
        @endforelse
    </ul>
</div>
