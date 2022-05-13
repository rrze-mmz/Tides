<div class="pt-10 pb-2 font-semibold border-b border-black font-2xl">
    {{ $layoutHeader }}
</div>
<div class="grid grid-cols-3 gap-4 pt-8 h48">
    @forelse($clips as $clip)
        @include('backend.clips._card',['clip'=> $clip])
    @empty
        {{ __('clip.common.no clips') }}
    @endforelse
</div>
