<div class="border-b border-black pt-10 pb-2 font-semibold font-2xl dark:text-white dark:border-white">
    {{ __('dashboard.files in dropzone') }}
</div>
<div class="mt-3 w-full rounded-md bg-white py-3 dark:bg-slate-800 dark:text-white">
    <ul>
        @forelse($files as $file)
            <li class="mt-2 mb-2 w-full p-2 font-normal">
                {{ $file['name'] }}
                <span class="text-sm italic text-gray-800 dark:text-white">
                    {{ __('dashboard.file last modified', ['modifiedDate' => $file['date_modified']]) }}
                </span>
            </li>
        @empty
            <li class="mt-2 mb-2 w-full p-2">
                {{ __('dashboard.no files found') }}
            </li>
        @endforelse
    </ul>
</div>
