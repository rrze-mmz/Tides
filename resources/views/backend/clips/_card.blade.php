@php use App\Enums\Acl; @endphp
<div class="flex my-2 w-full bg-white">
    <div class="flex justify-center justify-items-center  place-items-center mx-2 w-48 h-full">
        <img src="{{ fetchClipPoster($clip->latestAsset?->player_preview) }}" alt="preview image">
    </div>

    <div class="flex flex-col justify-between p-4 w-full bg-white">
        <div class="mb-1">
            <div class="text-sm font-bold text-gray-900">
                <a href="@if (str_contains(url()->current(), 'admin')) {{$clip->adminPath()}}
                @else {{ $clip->path() }} @endif"
                   class="underline"
                >{{ (request()->routeIs('clips.index') ||
                     request()->routeIs('frontend.clips.index') ||
                     request()->routeIs('search'))
                    ?$clip->title
                    :Str::limit($clip->title, 20, '...')}}
                </a>
            </div>
            <p class="py-3 text-base text-gray-700">
                {{ strip_tags(str_contains(url()->current(),'search')
                    ?$clip->description
                    :Str::limit($clip->description, 30)) }}
            </p>
        </div>
        <div class="flex items-center justify-content-between">
            <div class="pr-2">
                <x-heroicon-o-clock class="w-4 h-4"/>
            </div>
            <div class="text-sm">
                <p class="italic text-gray-900">
                    {{ $clip->recording_date }}
                </p>
            </div>
        </div>

        @if($clip->presenters->count() > 0)
            <div class="flex items-center pt-2 justify-content-between">
                <div class="pr-2">
                    <x-heroicon-o-user-group class="w-4 h-4"/>
                </div>
                <div class="text-sm">
                    <p class="italic text-gray-900">
                        {{ $clip->presenters
                            ->map(function($presenter){
                                return $presenter->getFullNameAttribute();
                            })->implode(',') }}
                    </p>
                </div>
            </div>
        @endif

        @if($clip->owner)
            <div class="flex items-center pt-2 justify-content-between">
                <div class="pr-2">
                    <x-heroicon-o-user class="w-4 h-4"/>
                </div>
                <div class="text-sm">
                    <p class="italic text-gray-900">
                        {{ $clip->owner?->getFullNameAttribute() }}
                    </p>
                </div>
            </div>
        @endif

        @if($clip->acls->isNotEmpty() )
            <div class="flex items-center pt-2 justify-content-between">
                <div class="pr-2">
                    @if(!$clip->acls->contains(\App\Enums\Acl::PUBLIC))
                        @can('watch-video', $clip)
                            <x-heroicon-o-lock-open class="w-4 h-4 text-green-500"/>
                            <span class="sr-only">Unlock clip</span>
                        @else
                            <x-heroicon-o-lock-closed class="w-4 h-4 text-red-700"/>
                            <span class="sr-only">Lock clip</span>
                        @endcan
                    @endif
                </div>
                <div class="text-sm">
                    <p class="italic text-gray-900">
                        {{ $clip->acls->except(Acl::PUBLIC())->pluck('name')->implode(', ') }}
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>
