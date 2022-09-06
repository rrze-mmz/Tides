<div class="pt-10 pb-2 mb-3 font-semibold border-b border-black font-2xl">
    Opencast running workflows
</div>
@if($opencastWorkflows['running']->isNotEmpty() && $opencastWorkflows['running']['workflows']['totalCount'] > 0)
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">Title
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
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Operation
                            </th>
                            <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                Prozent
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastWorkflows['running']['workflows']['workflow'] as $workflow)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $workflow['mediapackage']['title'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if(Illuminate\Support\Arr::exists($workflow['mediapackage'], 'seriestitle'))
                                        {{ $workflow['mediapackage']['seriestitle']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    {{ $workflow['mediapackage']['start'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    @if (isset($workflow['mediapackage']['creators']))
                                        {{ $workflow['mediapackage']['creators']['creator']  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-green-700">
                                    {{ $workflow['state'] }}
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-green-900">
                                    @foreach ($workflow['operations']['operation'] as $operation)
                                        @if($operation['state']==='RUNNING')
                                            {{ $operationDesc = $operation['description']}}
                                        @endif
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm font-light text-gray-900">
                                    <div class="mt-2 bg-gray-600 rounded-full">
                                        <div class="py-0 mt-2 w-6/12 bg-indigo-900 rounded-full">
                                            <div
                                                class="inline-block px-2 text-sm font-bold text-white bg-indigo-700 rounded-full">
                                                @foreach ($workflow['operations']['operation'] as $operation)
                                                    @if($operation['state']==='RUNNING')
                                                        {{ opencastWorkflowOperationPercentage( $operation['description']) }}
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
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

