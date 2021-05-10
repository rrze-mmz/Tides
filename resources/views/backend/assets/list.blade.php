<div class="flex pt-8 pb-2 font-semibold border-b border-black text-lg">
    Assets
</div>
<div class="flex">
    <ul class="pt-3 w-full">
        <li class="flex content-center items-center p-5 mb-4 bg-gray-400 rounded">
            <div class="pb-2 w-1/5 border-b border-black">ID</div>
            <div class="pb-2 w-3/5 border-b border-black">Saved path</div>
            <div class="pb-2 w-1/5 border-b border-black">Resolution</div>
            <div class="pb-2 w-1/5 border-b border-black">Actions</div>
        </li>

        @forelse($assets as $asset)
            <li class="flex content-center items-center p-5 mb-4 bg-gray-200 rounded">
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
                                class="py-2 px-8 text-white bg-red-500 rounded shadow hover:bg-red-600 focus:shadow-outline focus:outline-none"> Delete </button>
                    </form>
                </div>
            </li>
        @empty
            No assets
        @endforelse
    </ul>
</div>
