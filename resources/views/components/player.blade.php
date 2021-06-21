@if ($clip->acls->isEmpty())
    <video id="player" class="plyr-player" controls data-poster="{{ fetchClipPoster($clip->posterImage)  }}" >
        @if(Illuminate\Support\Facades\Storage::disk('streamable_videos')
                                                    ->exists($clip->assets()->first()->id.'.m3u8'))
            <source src="{{ '/streamable_videos/'.$clip->assets()->first()->id . '.m3u8'  }}"
                    type="application/x-mpegURL" />
        @elseif(Illuminate\Support\Str::contains($wowzaStatus['0'],'Wowza Streaming Engine'))
            <source
                src="{{ env('WOWZA_ENGINE_URL').$clip->getCameraSmil()?->path.'/playlist.m3u8' }}"
                type="application/x-mpegURL"  />
        @else
            <source  src="{{ '/videos/'.$clip->assets->first()->path  }}"
                     type="video/mp4" />
    @endif
    <!-- Captions are optional -->
    </video>
@else
    @can('view-video', $clip)
        <video id="player" class="plyr-player" controls data-poster="{{ fetchClipPoster($clip->posterImage)  }}" >
            @if(Illuminate\Support\Facades\Storage::disk('streamable_videos')
                                                        ->exists($clip->assets()->first()->id.'.m3u8'))
                <source src="{{ '/streamable_videos/'.$clip->assets()->first()->id . '.m3u8'  }}"
                        type="application/x-mpegURL" />
            @elseif(Illuminate\Support\Str::contains($wowzaStatus['0'],'Wowza Streaming Engine'))
                <source
                    src="{{ env('WOWZA_ENGINE_URL').$clip->getCameraSmil()?->path.'/playlist.m3u8' }}"
                    type="application/x-mpegURL"  />
            @else
                <source  src="{{ '/videos/'.$clip->assets->first()->path  }}"
                         type="video/mp4" />
        @endif
        <!-- Captions are optional -->
        </video>
    @else
        <p>You are not authorized to view this video!</p>
    @endcan
@endif


