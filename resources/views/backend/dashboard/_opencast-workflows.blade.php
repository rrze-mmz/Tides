<div class="pt-10 pb-2 mb-3 font-semibold border-b border-black font-2xl">
    Opencast running workflows
</div>
@if($opencastWorkflows['running']->isNotEmpty())

    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Recording date
                            </th>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Presenter
                            </th>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Status
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastWorkflows['running'] as $workflow)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $workflow['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if(!empty($workflow['series']))
                                        {{ $workflow['series']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $workflow['created'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if (isset($workflow['mediapackage']['creators']))
                                        {{ $workflow['mediapackage']['creators']['creator']  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-green-700">
                                    {{ $workflow['processing_state'] }}
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

<div class="pt-10 pb-2 mb-3 font-semibold border-b border-black font-2xl">
    Opencast failed workflows
</div>
@if($opencastWorkflows['failed']->isNotEmpty())
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Series
                            </th>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Date
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastWorkflows['failed'] as $workflow)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                                    {{ $workflow['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                                    {{ $workflow['series'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
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

