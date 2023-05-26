<video id="player" class="plyr-tides" controls
       data-poster="{{ fetchClipPoster($clip->latestAsset?->player_preview)  }}">
    @if(Illuminate\Support\Facades\Storage::disk('streamable_videos')
                                                ->exists($clip->assets()->first()->id.'.m3u8'))
        <source src="{{ '/streamable_videos/'.$clip->assets()->first()->id . '.m3u8'  }}"
                type="application/x-mpegURL"/>
    @elseif($wowzaStatus->contains('pass'))
        <source
            src="{{ $wowzaService->vodSecureUrl($clip) }}"
            type="application/x-mpegURL"/>

        @if($captionAsset = $clip->getCaptionAsset())
            <track kind="captions" label="DE" src="{{getProtectedUrl($captionAsset->path)}}" srclang="de" default/>
        @endisset
    @else
        <source src="{{ '/videos/'.$clip->assets->first()->path  }}"
                type="video/mp4"/>
    @endif
</video>


