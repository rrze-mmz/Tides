<div class="pt-10 pb-2 font-semibold border-b border-black font-2xl">
    {{ $layoutHeader }}
</div>
<div class="grid grid-cols-3 gap-4 pt-8 h48">
    @forelse($series as $single)
        @include('backend.series._card',['series'=> $single])
    @empty
        No series found
    @endforelse
</div>

<div class="py-10">
    {{ $series->links() }}
</div>
