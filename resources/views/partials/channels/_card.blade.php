<div
    class="m-2 rounded-lg border-2 border-solid border-black dark:border-white p-2  dark:bg-slate-800 ">
    <div class="flex place-content-around justify-between">
        <div>
            <h3 class="pb-6 font-semibold dark:text-white">
                {{ $channel->name }} Channel
            </h3>
        </div>
        <div>
            <x-heroicon-o-check-circle class="h-6 w-6 rounded text-green-600" />
        </div>
    </div>
    <div class="flex items-center space-x-2 dark:text-white">
        <x-heroicon-o-user class="h-6 w-5" />
        <span>{{ $channel->owner->getFullNameAttribute() }}</span>
    </div>
    <div class="pt-5">
        <a
            href="@if(!str_contains(url()->current(), 'admin')){{ route('frontend.channels.show', $channel) }}
                  @else{{ route('channels.edit', $channel) }}
                  @endif"
            class="flex flex-row">
            <x-button type="button"
                      class="flex lg:basis-1/2 md:w-full content-center justify-between bg-blue-600 hover:bg-blue-700">
                <div>
                    @if(!str_contains(url()->current(), 'admin'))
                        View channel
                    @else
                        Go to channel edit page
                    @endif

                </div>
                <div>
                    <x-heroicon-o-arrow-circle-right class="w-6" />
                </div>
            </x-button>
        </a>
    </div>
</div>