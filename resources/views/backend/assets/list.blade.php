@php use App\Enums\Content; @endphp
@can('administrate-superadmin-portal-pages')
    <div
            class="flex border-b border-black py-4 text-lg font-semibold dark:text-white dark:border-white"
    >
        {{ __('asset.backend.Assets / FolderID', ['folder_id' => $obj->folder_id]) }}
    </div>
@endcan
<div class="flex">
    <ul class="w-full pt-3">
        @php
            $assetsList = $assets->filter(function ($item){
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
                <div class="w-4/12"> {{ $asset->original_file_name }} | ID [{{ $asset->id }}]</div>
                <div class="w-2/12">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium
                    bg-green-500">
                        {{ Str::lower(Content::tryFrom($asset->type)->name) }}
                    </span>
                </div>
                <div class="w-2/12"> {{ $asset->durationToHours() }}</div>
                <div class="w-2/12"> {{ $asset->width }} x {{ $asset->height }}</div>
                <div class="flex w-2/12 items-center align-items-center space-x-2">
                    <a href="{{ route('assets.download',$asset) }}">
                        <x-button class="bg-blue-600 hover:bg-blue-700">
                            {{ __('common.actions.download') }}
                        </x-button>
                    </a>
                    <x-modals.delete
                            :route="route('assets.destroy', $asset)"
                            class="w-full justify-center"
                    >
                        <x-slot:title>
                            {{ __('asset.backend.delete.modal title',[
                            'asset_original_file_name'=>$asset->original_file_name
                            ]) }}
                        </x-slot:title>
                        <x-slot:body>
                            {{ __('asset.backend.delete.modal body') }}
                        </x-slot:body>
                    </x-modals.delete>
                </div>
            </li>
        @empty
            <div class="flex mx-auto place-content-center">
                <div class="text-2xl dark:text-white italic py-8">
                    {{ __('asset.common.no assets found') }}
                </div>
            </div>
        @endforelse
    </ul>
</div>
