@php use App\Enums\Content; @endphp
<div
    class="flex border-b border-black py-4 text-lg font-semibold dark:text-white dark:border-white"
>
    Assets / FolderID: {{ $clip->folder_id }}
</div>
<div class="flex">
    <ul class="w-full pt-3">
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
                <div class="flex w-2/12 items-center align-items-center space-x-2">
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
            <div class="flex text-center items-center">
                <div class="text-lg dark:text-white">
                    No assets
                </div>
            </div>
        @endforelse
    </ul>
</div>
