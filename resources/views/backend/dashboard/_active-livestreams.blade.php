<div class="flex flex-col py-2">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption
                    class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-gray-200 dark:text-white
                    dark:bg-gray-800">
                {{ __('livestream.backend.livestream rooms', ['counter' => $activeLivestreams->count() ]) }}
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-white">
            <tr>
                <th scope="col"
                    class="px-6 py-3 ">
                    {{ __('livestream.common.room') }}
                </th>
                <th scope="col"
                    class="px-6 py-3">
                    {{ __('livestream.common.room') }}
                </th>
                <th scope="col"
                    class="px-6 py-3">
                    {{ __('livestream.common.stream type') }}
                </th>

                <th scope="col"
                    class="px-6 py-3">
                    {{ __('livestream.common.Stream start') }}
                </th>
                <th scope="col"
                    class="px-6 py-3">
                    {{ __('livestream.common.Stream end') }}
                </th>
                <th scope="col"
                    class="px-6 py-3">
                    {{ __('livestream.common.online viewers') }}
                </th>
                <th scope="col"
                    class="px-6 py-3">
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($activeLivestreams->get() as $activeLivestream)
                <tr class="border-b bg-green-200 font-normal text-gray-900 dark:bg-green-800 dark:text-white">
                    <td class="px-6 py-4 text-sm ">
                        @if($activeLivestream->clip_id)
                            {{ $activeLivestream->clip->title }}
                        @else
                            {{ __('livestream.common.hidden stream') }}
                        @endif

                    </td>
                    <td class="px-6 py-4 text-sm ">
                        {{ $activeLivestream->name }}
                    </td>
                    <td class="px-6 py-4 text-sm ">
                        SBS
                    </td>
                    <td class="px-6 py-4 text-sm ">
                        {{ $activeLivestream->time_availability_start }}
                    </td>
                    <td class="px-6 py-4 text-sm ">
                        {{ $activeLivestream->time_availability_end }}
                    </td>
                    <td class="px-6 py-4 text-sm ">
                        {{ rand(0, 200) }}
                    </td>
                    <td class="px-6 py-4 text-sm flex space-x-2 items-center">
                        <div>
                            <a href="{{ route('livestreams.edit', $activeLivestream) }}">
                                <x-heroicon-c-pencil class="h-6" />
                            </a>
                        </div>
                        @if($activeLivestream->clip_id)
                            <div>
                                <a href="{{ route('frontend.clips.show', $activeLivestream->clip) }}">
                                    <x-heroicon-c-eye class="h-6" />
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
