@use('Illuminate\Support\Carbon')

<div class=" text-xl font-bold text-left rtl:text-right text-gray-500 dark:text-gray-400 pb-4">
    Geolocation Stats
</div>

<div class="relative overflow-x-auto">
    <table class="w-2/3 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3">
                Month
            </th>
            <th scope="col" class="px-6 py-3">
                Bayern
            </th>
            <th scope="col" class="px-6 py-3">
                Germany
            </th>
            <th scope="col" class="px-6 py-3">
                World
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($obj['geoLocationStats']['monthlyData'] as $month=>$stats)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ Carbon::parse($month)->format('Y - F') }}
                </th>
                <td class="px-6 py-4">
                    {{ $stats['total_bavaria'] }}
                </td>
                <td class="px-6 py-4">
                    {{ $stats['total_germany'] }}
                </td>
                <td class="px-6 py-4">
                    {{ $stats['total_world'] }}
                </td>
            </tr>
        @endforeach
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                Total
            </th>
            <td class="px-6 py-4">
                {{ $obj['geoLocationStats']['total']['total_bavaria'] }}
            </td>
            <td class="px-6 py-4">
                {{ $obj['geoLocationStats']['total']['total_germany'] }}
            </td>
            <td class="px-6 py-4">
                {{ $obj['geoLocationStats']['total']['total_world'] }}
            </td>
        </tr>
        </tbody>
    </table>
</div>
