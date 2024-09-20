@php use App\Models\Stats\AssetViewCount;use Illuminate\Support\Carbon; @endphp
<div class="border-b border-black pt-10 pb-2 font-semibold font-2xl dark:text-white dark:border-white">
    {{ __('clip.common.trending clips') }}
</div>
<div class="mt-3 w-full rounded-md bg-white py-3 dark:bg-slate-800 dark:text-white">
    <ul>
        @foreach(AssetViewCount::trendingClips(Carbon::now()->subMonth()) as $clip)
            <li class="mt-2 mb-2 w-full p-2 font-normal flex items-center space-x-2">
                <div>
                    <x-heroicon-o-arrow-right-circle class="h-6" />
                </div>
                <div>
                    <a href="{{ route('clips.edit', $clip['info']) }}">
                        {{ $clip['info']['title'] }}
                    </a>
                </div>
                <div>
                    <span class="text-gray-800 dark:text-white flex items-center space-x-2">
                        <div>
                            <x-heroicon-o-eye class="h-6" />
                        </div>
                        <div class="font-bold italic ">
                            {{ $clip['counter'] }}
                        </div>
                    </span>
                </div>
            </li>
        @endforeach
    </ul>
</div>
