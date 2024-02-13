<div class="border-b border-black pt-5 pb-2 font-semibold font-2xl  dark:text-white dark:border-white">
    {{ $layoutHeader }}
</div>
<div class="grid xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-2  gap-4 pt-4 px-4">
    @forelse($series as $single)
        @include('backend.series._card',['series'=> $single])
    @empty
        <div class="dark:text-white">
            {{  __('homepage.series.no series found') }}
        </div>
    @endforelse
</div>

<div class="py-10">
    {{ $series->links() }}
</div>

