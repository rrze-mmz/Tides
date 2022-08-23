@extends('layouts.backend')

@section('content')

    <div class="flex pb-2 font-semibold border-b border-black font-2xl align-items-center items-center">
        <div class="pr-4">
            Systems check
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 pt-4">
        <div class="m-2 p-2 border-black border-solid rounded-lg border-2">
            <div class="flex  justify-between place-content-around">
                <div>
                    <h3 class="pb-6 font-semibold text-green-500">Opencast
                    </h3>
                </div>
                <div>
                    @if($opencastStatus->contains('pass'))
                        <x-heroicon-o-check-circle class="w-6 h-6 rounded text-green-600"/>
                    @else
                        <x-heroicon-o-x-circle class="w-6 h-6 rounded text-red-600"/>
                    @endif
                </div>
            </div>

            <div>
                <div>
                    Opencast version:<span class=" font-bold"> {{$opencastStatus['releaseId']}} </span>
                </div>
                <div>
                    Description : <span class=" font-bold"> {{$opencastStatus['description']}} </span>
                </div>
            </div>
        </div>
        <div class="m-2 p-2 border-black border-solid rounded-lg border-2">
            <div class="flex  justify-between place-content-around">
                <div>
                    <h3 class="pb-6 font-semibold text-orange-500">Wowza
                    </h3>
                </div>
                <div>
                    @if($wowzaStatus->contains('pass'))
                        <x-heroicon-o-check-circle class="w-6 h-6 rounded text-green-600"/>
                    @else
                        <x-heroicon-o-x-circle class="w-6 h-6 rounded text-red-600"/>
                    @endif
                </div>
            </div>

            <div>
                <div>
                    Wowza description:<span class=" font-bold"> {{str($wowzaStatus['releaseId'])->remove('"')}} </span>
                </div>
            </div>
        </div>

        <div class="m-2 p-2 border-black border-solid rounded-lg border-2">
            <div class="flex  justify-between place-content-around">
                <div>
                    <h3 class="pb-6 font-semibold">Elasticsearch
                    </h3>
                </div>
                <div>
                    @if($elasticsearchStatus->contains('pass'))
                        <x-heroicon-o-check-circle class="w-6 h-6 rounded text-green-600"/>
                    @else
                        <x-heroicon-o-x-circle class="w-6 h-6 rounded text-red-600"/>
                    @endif
                </div>
            </div>

            <div>
                <div>
                    Version:<span
                        class=" font-bold"> {{ $elasticsearchStatus->get('releaseId')['version']['number'] }} </span>
                </div>
                <div>
                    Description:<span
                        class=" font-bold">{{ $elasticsearchStatus->get('releaseId')['version']['build_type'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

@endsection
