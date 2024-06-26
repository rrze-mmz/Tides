@php use Barryvdh\Debugbar\Facades\Debugbar;use Barryvdh\Debugbar\Twig\Extension\Debug; @endphp
<div id="player" data-plyr-provider="html5" data-plyr-embed-id="video">
    <video id="video" class="plyr__video" playsinline controls
           data-poster="{{ fetchClipPoster($clip->latestAsset?->player_preview)  }}">
        @if($clip->is_livestream)
            <source
                src="{{ $defaultVideoUrl }}"
                type="video/mp4"
            />
        @elseif(Illuminate\Support\Facades\Storage::disk('streamable_videos')
                                                    ->exists($clip->assets()->first()->id.'.m3u8'))
            <source src="{{ '/streamable_videos/'.$clip->assets()->first()->id . '.m3u8'  }}"
                    type="application/x-mpegURL"
            />
        @elseif($wowzaStatus->contains('pass') && !empty($defaultVideoUrl))
            <source
                src="{{ $defaultVideoUrl }}"
                type="application/x-mpegURL"
            />
            @if($captionAsset = $clip->getCaptionAsset())
                <track kind="captions"
                       label="DE"
                       src="{{getProtectedUrl($captionAsset->path)}}"
                       srclang="de"
                       default
                />
            @endisset
        @else
            <source src="{{ '/videos/'.$clip->assets->first()->path  }}"
                    type="video/mp4"
            />
        @endif
    </video>
</div>
