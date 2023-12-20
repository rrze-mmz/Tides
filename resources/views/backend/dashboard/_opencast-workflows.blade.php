@use(App\Enums\OpencastWorkflowState)
@use(Carbon\Carbon)

@if(isset($opencastEvents['recording']) && $opencastEvents['recording']->isNotEmpty())
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl dark:text-white dark:border-white">
        {{ $opencastEvents['recording']->count() }} Recording events
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b dark:bg-slate-800-800 dark:text-white font-normal text-sm text-gray-900
                        text-left ">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 ">
                                Title
                            </th>
                            <th scope="col"
                                class="px-6 py-4">
                                Series
                            </th>
                            <th scope="col"
                                class="px-6 py-4">
                                Presenter
                            </th>

                            <th scope="col"
                                class="px-6 py-4">
                                Start time
                            </th>
                            <th scope="col"
                                class="px-6 py-4">
                                Location
                            </th>
                            <th scope="col"
                                class="px-6 py-4">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['recording'] as $event)
                            <tr class="border-b bg-white font-normal text-gray-900 dark:bg-gray-800 dark:text-white">
                                <td class="px-6 py-4 text-sm ">
                                    {{ $event['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if(!empty($event['series']))
                                        <div class="flex items-center">
                                            <div>
                                                {{ $event['series']  }}
                                            </div>
                                            @if ( Request::segment(1) === 'admin')
                                                <div class="pl-2">
                                                    <a href="{{ route('series.edit', str($event['series'])->after('courseID:'))}}">
                                                        <x-heroicon-o-arrow-circle-right class="h-6" />
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm ">
                                    @if (isset($event['presenter'][0]))
                                        {{ $event['presenter'][0]  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm ">
                                    {{ zuluToCEST($event['start']) }}
                                </td>
                                <td class="px-6 py-4 text-sm ">
                                    {{ $event['location'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-red-700">
                                    {{ OpencastWorkflowState::tryFrom($event['status'])->lower() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@if(isset($opencastEvents['scheduled']) && $opencastEvents['scheduled']->isNotEmpty())
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl dark:text-white dark:border-white">
        {{ $opencastEvents['scheduled']->count() }} Opencast scheduled events for today
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b dark:bg-slate-800-800 dark:text-white font-normal text-sm text-gray-900
                        text-left ">
                        <tr>
                            <th scope="col" class="px-6 py-4">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Presenter
                            </th>

                            <th scope="col" class="px-6 py-4">
                                Start time
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['scheduled'] as $event)
                            <tr class="border-b bg-white font-normal text-gray-900 dark:bg-gray-800 dark:text-white">
                                <td class="px-6 py-4 text-sm font-light">
                                    {{ $event['title'] }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($event['series']))
                                        {{ $event['series']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if (isset($event['presenter'][0]))
                                        {{ $event['presenter'][0]  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ zuluToCEST($event['start']) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $event['location'] }}
                                </td>
                                <td class="px-6 py-4 text-green-700">
                                    {{ OpencastWorkflowState::tryFrom($event['status'])->lower() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

@if(isset($opencastEvents['trimming']) && $opencastEvents['trimming']->isNotEmpty())
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl dark:text-white dark:border-white">
        {{ $opencastEvents['trimming']->count() }} Opencast events waiting for trim
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b dark:bg-slate-800-800 dark:text-white font-normal text-sm text-gray-900
                        text-left ">
                        <tr>
                            <th scope="col" class="px-6 py-4">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Presenter
                            </th>

                            <th scope="col" class="px-6 py-4">
                                Start time
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['trimming'] as $event)
                            <tr class="border-b bg-white font-normal text-gray-900 dark:bg-gray-800 dark:text-white">
                                <td class="px-6 py-4">
                                    {{ $event['title'] }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($event['series']))
                                        {{ $event['series']['title']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if (isset($event['presenters']) && !empty($event['presenters']))
                                        {{ $event['presenters'][0]  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ zuluToCEST($event['start_date']) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $event['location'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-green-700">
                                    waiting for trim
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif


@if($opencastEvents['running']->isNotEmpty())
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl dark:text-white dark:border-white">
        {{ $opencastEvents['running']->count() }} Opencast running workflows
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b dark:bg-slate-800-800 dark:text-white font-normal text-sm text-gray-900
                        text-left">
                        <tr>
                            <th scope="col" class="px-6 py-4">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Recording date
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Presenter
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['running'] as $events)
                            <tr class="border-b bg-white font-normal text-gray-900 dark:bg-gray-800 dark:text-white">
                                <td class="px-6 py-4">
                                    {{ $events['title'] }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($events['series']))
                                        {{ $events['series']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $events['created'] }}
                                </td>
                                <td class="px-6 py-4">
                                    @if (isset($events['mediapackage']['creators']))
                                        {{ $events['mediapackage']['creators']['creator']  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-green-700">
                                    {{ $events['processing_state'] }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

@if($opencastEvents['failed']->isNotEmpty())
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl dark:text-white dark:border-white">
        Opencast failed events
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b dark:bg-slate-800-800 dark:text-white font-normal text-sm text-gray-900
                        text-left">
                        <tr>
                            <th scope="col" class="px-6 py-4">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Date
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['failed'] as $workflow)
                            <tr class="border-b bg-white font-normal text-gray-900 dark:bg-gray-800 dark:text-white">
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $workflow['title'] }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ $workflow['series'] }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 ">
                                    {{ $workflow['start'] }}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

@if(isset($opencastEvents['upcoming']) && $opencastEvents['upcoming']->isNotEmpty())
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl">
        {{ $opencastEvents['upcoming']->count() }} upcoming Opencast events
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b dark:bg-slate-800-800 dark:text-white font-normal text-sm text-gray-900
                        text-left">
                        <tr>
                            <th scope="col" class="px-6 py-4">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Presenter
                            </th>

                            <th scope="col" class="px-6 py-4">
                                Start time
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-4">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['upcoming'] as $event)
                            <tr class="border-b bg-white font-normal text-gray-900 dark:bg-gray-800 dark:text-white">
                                <td class="px-6 py-4 ">
                                    {{ $event['title'] }}
                                </td>
                                <td class="px-6 py-4 ">
                                    @if(!empty($event['series']))
                                        {{ $event['series']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 ">
                                    @if (isset($event['presenter'][0]))
                                        {{ $event['presenter'][0]  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 ">
                                    {{ zuluToCEST($event['start']) }}
                                </td>
                                <td class="px-6 py-4 ">
                                    {{ $event['location'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-green-700">
                                    {{ OpencastWorkflowState::tryFrom($event['status'])->lower() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

