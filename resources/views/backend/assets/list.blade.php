@php use App\Enums\Content; @endphp
<div
    class="flex border-b border-black pt-8 pb-2 text-lg font-semibold dark:text-white dark:border-white"
>
    Assets / FolderID: {{ $clip->folder_id }}
</div>
<div class="flex">
    <ul class="w-full pt-3">
        <li class="mb-4 flex rounded bg-gray-400 p-5 align-middle  content-center items-center dark:bg-gray-800
            dark:border-white dark:text-white">
            <div class="w-4/12 border-b border-black pb-2 dark:border-white">File Name</div>
            <div class="w-2/12 border-b border-black pb-2 dark:border-white">Type</div>
            <div class="w-2/12 border-b border-black pb-2 dark:border-white">Duration</div>
            <div class="w-2/12 border-b border-black pb-2 dark:border-white">Resolution</div>
            <div class="w-2/12 border-b border-black pb-2 dark:border-white">Actions</div>
        </li>
        @php
            $assetsList = $assets->filter(function ($item) use ($clip) {
                    // If the item is a SMIL asset, check if the user is an admin
                    if ($item->type == Content::SMIL->value) {
                        return Gate::allows('administrate-admin-portal-pages');
                    }
                // For all other types, include them in the list
                return true;
                })
                ->sortByDesc(['type', 'height'])
        @endphp
        @forelse($assetsList as $asset)
            <li class="mb-4 flex rounded bg-gray-200 p-2 text-sm  align-middle  content-center items-center
                       font-normal dark:bg-gray-800 dark:text-white">
                <div class="w-4/12"> {{ $asset->original_file_name }}</div>
                <div class="w-2/12">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium
                    bg-green-500">
                        {{ Str::lower(Content::tryFrom($asset->type)->name) }}
                    </span>
                </div>
                <div class="w-2/12"> {{ $asset->durationToHours() }}</div>
                <div class="w-2/12"> {{ $asset->width }} x {{ $asset->height }}</div>
                <div class="flex w-2/12 items-center align-items-center space-x-1">
                    <x-form.button :link="route('assets.download',$asset)" type="submit" text="Download" />
                    <form method="POST"
                          action="{{$asset->path() }}"
                    >
                        @csrf
                        @method('DELETE')
                        <x-form.button :link="$link=false" type="delete" text="Delete" color="red" />
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
