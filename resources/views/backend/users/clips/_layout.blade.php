<div class="border-b border-black pt-5 pb-2 font-semibold font-2xl dark:text-white dark:border-white">
    {{ $layoutHeader }}
</div>
<div class="grid xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-4 sm:grid-cols-12  gap-4 pt-4">
    @forelse($clips as $clip)
        @include('backend.clips._card',['clip'=> $clip])
    @empty
        <div class="text-white">
            {{ __('clip.common.no clips') }}
        </div>
    @endforelse
</div>
