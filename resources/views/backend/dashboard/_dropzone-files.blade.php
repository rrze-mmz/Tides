<div class="pt-10 pb-2 font-semibold border-b border-black font-2xl">
    Files in drop zone
</div>
<div class="w-full mt-3 py-3 bg-white">
    <ul>
        @forelse($files as $file)
            <li class="w-full mt-2 mb-2 p-2">
                {{ $file['name'] }}
                <span class="font-italic text-sm text-gray-800"> last modified at
                                            {{ $file['date_modified'] }}
                                        </span>
            </li>
        @empty
            <li class="w-full mt-2 mb-2 p-2">
                No files found
            </li>
        @endforelse
    </ul>
</div>
