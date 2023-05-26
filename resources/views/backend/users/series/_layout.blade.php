<div class="pt-5 pb-2 font-semibold border-b border-black font-2xl">
    {{ $layoutHeader }}
</div>
<div class="grid grid-cols-6 gap-4 pt-4">
    @forelse($series as $single)
        @include('backend.series._card',['series'=> $single])
    @empty
        No series found
    @endforelse
</div>

<div class="py-10">
    {{ $series->links() }}
</div>
