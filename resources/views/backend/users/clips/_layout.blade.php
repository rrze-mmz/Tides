<div class="border-b border-black pt-5 pb-2 font-semibold font-2xl">
    {{ $layoutHeader }}
</div>
<div class="grid grid-cols-3 gap-4 pt-4">
    @forelse($clips as $clip)
        @include('backend.clips._card',['clip'=> $clip])
    @empty
        {{ __('clip.common.no clips') }}
    @endforelse
</div>
