@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-auto md:mt-32">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>

        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold dark:text-white"> Active channels </h2>
        </div>
        <div class="grid gap-4 pt-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 font-normal
            dark:text-white">
            @forelse($channels as $channel)
                <div class="m-2 rounded-lg border-2 border-solid border-black dark:border-white p-2">
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
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-user class="h-6 w-5 dark:text-white" />
                        <span>{{ $channel->owner->getFullNameAttribute() }}</span>
                    </div>
                    <div class="pt-5">
                        <a href="{{ route('frontend.channels.show', $channel) }}" class="flex flex-row">
                            <x-button type="button"
                                      class="flex basis-1/2 content-center justify-between bg-blue-600 hover:bg-blue-700">
                                <div>
                                    Visit channel
                                </div>
                                <div>
                                    <x-heroicon-o-arrow-circle-right class="w-6" />
                                </div>
                            </x-button>
                        </a>
                    </div>
                </div>
            @empty
                <div class="flex justify-center">
                    <div class="dark:text-white pt-10 text-2xl"> No channels available</div>
                </div>
            @endforelse
        </div>
    </main>
@endsection
