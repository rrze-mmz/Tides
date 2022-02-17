<div class="flex flex-col">
    <div class="flex justify-center content-center pt-6 ">

        @if($clip->checkAcls())
            <x-player :clip="$clip" :wowzaStatus="$wowzaStatus"/>
        @else
            <p>You are not authorized to view this video!</p>
        @endif

    </div>
    <div class="flex justify-around pt-8 pb-3 border-b-2 border-gray-500">

        <div class="flex items-center">
            <x-heroicon-o-user-group class="h-6 w-6"/>
            <span class="pl-3"> {{ $clip->presenters->pluck(['full_name'])->implode(', ') }} </span>
        </div>


        <div class="flex items-center">
            <x-heroicon-o-clock class="w-6 h-6"/>
            <span class="pl-3"></span> {{ $clip->assets()->first()->durationToHours() }} Min
        </div>

        <div class="flex items-center">
            <x-heroicon-o-calendar class="w-6 h-6"/>
            <span class="pl-3">{{ $clip->created_at->format('Y-m-d') }}</span>
        </div>

        <div class="flex items-center">
            <x-heroicon-o-upload class="w-6 h-6"/>
            <span class="pl-3"> {{ $clip->assets->first()->updated_at }}</span>
        </div>

        <div class="flex items-center">
            <x-heroicon-o-eye class="w-6 h-6"/>
            <span class="pl-3"> 0 Views </span>
        </div>
    </div>

</div>
