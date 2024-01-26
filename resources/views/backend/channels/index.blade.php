@use(\Illuminate\Support\Str)
@extends('layouts.backend')

@section('content')
    @if($channels->isEmpty())
        @include('backend.channels.activate._form')
    @else
        <div class="flex items-center border-b border-black pb-2 font-semibold font-2xl align-items-center
    dark:text-white dark:border-white">
            <div class="pr-4">
                Your channels
            </div>
        </div>

        <div class="grid gap-4 pt-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-2 2xl:grid-cols-2 font-normal
            dark:text-white">
            @foreach($channels as $channel)
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

                    <div>
                        <div>
                            {{ $channel->description }}
                        </div>
                    </div>
                    <div class="pt-5">
                        <a href="{{ route('settings.portal.show') }}" class="flex flex-row">
                            <x-button type="button"
                                      class="flex basis-1/2 content-center justify-center justify-between bg-blue-600 hover:bg-blue-700">
                                <div>
                                    Go to channel edit page
                                </div>
                                <div>
                                    <x-heroicon-o-arrow-circle-right class="w-6" />
                                </div>
                            </x-button>
                        </a>
                    </div>
                </div>
        </div>
        @endforeach

    @endif

@endsection
