<video id="player" class="plyr-player" controls data-poster="{{ fetchClipPoster($clip->posterImage)  }}">
    @if(Illuminate\Support\Facades\Storage::disk('streamable_videos')
                                                ->exists($clip->assets()->first()->id.'.m3u8'))
        <source src="{{ '/streamable_videos/'.$clip->assets()->first()->id . '.m3u8'  }}"
                type="application/x-mpegURL"/>
    @elseif(Illuminate\Support\Str::contains($wowzaStatus['0'],'Wowza Streaming Engine'))
        <source
            src="{{ config('wowza.stream_url').getClipStoragePath($clip).$clip->getAssetsByType('smil')->first()?->original_file_name.'/playlist.m3u8' }}"
            type="application/x-mpegURL"/>
    @else
        <source src="{{ '/videos/'.$clip->assets->first()->path  }}"
                type="video/mp4"/>
@endif
<!-- Captions are optional -->
</video>


