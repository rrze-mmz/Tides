<div class="border-b border-black pt-5 pb-2 font-semibold font-2xl">
    {{ $layoutHeader }}
</div>
<div class="grid xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-4 sm:grid-cols-12  gap-4 pt-4">
    @forelse($series as $single)
        @include('backend.series._card',['series'=> $single])
    @empty
        No series found
    @endforelse
</div>

<div class="py-10">
    {{ $series->links() }}
</div>
