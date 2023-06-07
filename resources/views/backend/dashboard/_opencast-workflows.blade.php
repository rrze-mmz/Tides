@php use App\Enums\OpencastWorkflowState;use Carbon\Carbon; @endphp
@if(isset($opencastEvents['recording']) && $opencastEvents['recording']->isNotEmpty())
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl">
        {{ $opencastEvents['recording']->count() }} Recording events
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Presenter
                            </th>

                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Start time
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['recording'] as $event)
                            <tr class="border-b bg-white">
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $event['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if(!empty($event['series']))
                                        {{ $event['series']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if (isset($event['presenter'][0]))
                                        {{ $event['presenter'][0]  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ zuluToCEST($event['start']) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $event['location'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-red-700">
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
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl">
        {{ $opencastEvents['scheduled']->count() }} Opencast scheduled events for today
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Presenter
                            </th>

                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Start time
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['scheduled'] as $event)
                            <tr class="border-b bg-white">
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $event['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if(!empty($event['series']))
                                        {{ $event['series']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if (isset($event['presenter'][0]))
                                        {{ $event['presenter'][0]  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ zuluToCEST($event['start']) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
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

@if(isset($opencastEvents['trimming']) && $opencastEvents['trimming']->isNotEmpty())
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl">
        {{ $opencastEvents['trimming']->count() }} Opencast events waiting for trim
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Presenter
                            </th>

                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Start time
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['trimming'] as $event)
                            <tr class="border-b bg-white">
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $event['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if(!empty($event['series']))
                                        {{ $event['series']['title']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if (isset($event['presenters']) && !empty($event['presenters']))
                                        {{ $event['presenters'][0]  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ zuluToCEST($event['start_date']) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
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
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl">
        {{ $opencastEvents['running']->count() }} Opencast running workflows
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Recording date
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Presenter
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['running'] as $events)
                            <tr class="border-b bg-white">
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $events['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if(!empty($events['series']))
                                        {{ $events['series']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $events['created'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
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
    <div class="mb-3 border-b border-black pt-10 pb-2 font-semibold font-2xl">
        Opencast failed events
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Date
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['failed'] as $workflow)
                            <tr class="border-b bg-white">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $workflow['title'] }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $workflow['series'] }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-light text-gray-900">
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
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Presenter
                            </th>

                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Start time
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Location
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastEvents['upcoming'] as $event)
                            <tr class="border-b bg-white">
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $event['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if(!empty($event['series']))
                                        {{ $event['series']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if (isset($event['presenter'][0]))
                                        {{ $event['presenter'][0]  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ zuluToCEST($event['start']) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
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

