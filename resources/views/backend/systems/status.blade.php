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
                    <h3 class="pb-6 font-semibold">Opencast
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
        <div class="border-black border-solid rounded-lg border-2 m-2 p-2">
            <h3>Wowza</h3>

            <div>
                <p>Status: $opencast</p>
            </div>
        </div>

        <div class="border-black border-solid rounded-lg border-2 m-2 p-2">
            <h3>Elasticsearch node</h3>

            <div>
                <p>Status: $opencast</p>
            </div>
        </div>
    </div>

@endsection
