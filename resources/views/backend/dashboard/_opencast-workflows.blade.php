<div class="pt-10 pb-2 font-semibold border-b border-black font-2xl mb-3">
    Opencast running workflows
</div>
@if(($opencastWorkflows['running']['workflows']['totalCount'] > 0))
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Title
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Series
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Recording date
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Presenter
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Status
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Operation
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Prozent
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastWorkflows['running']['workflows']['workflow'] as $workflow)
                            <tr class="bg-white border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 ">
                                    {{ $workflow['mediapackage']['title'] }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 ">
                                    @if(Illuminate\Support\Arr::exists($workflow['mediapackage'], 'seriestitle'))
                                        {{ $workflow['mediapackage']['seriestitle']  }}
                                    @else
                                        {{ 'EVENTS_WITHOUT_SERIES' }}
                                    @endif
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 ">
                                    {{ $workflow['mediapackage']['start'] }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 ">
                                    @if (isset($workflow['mediapackage']['creators']))
                                        {{ $workflow['mediapackage']['creators']['creator']  }}
                                    @else
                                        {{ 'No presenter' }}
                                    @endif
                                </td>
                                <td class="text-sm text-green-700 font-light px-6 py-4 ">
                                    {{ $workflow['state'] }}
                                </td>
                                <td class="text-sm text-green-900 font-light px-6 py-4 ">
                                    @foreach ($workflow['operations']['operation'] as $operation)
                                        @if($operation['state']==='RUNNING')
                                            {{$operation['description']}}
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 ">
                                    <div class="mt-2 bg-gray-600 rounded-full">
                                        <div class="w-6/12 mt-2 bg-indigo-900 py-0 rounded-full">
                                            <div
                                                class=" text-white font-bold text-sm inline-block bg-indigo-700 px-2 rounded-full">
                                                50%
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

<div class="pt-10 pb-2 font-semibold border-b border-black font-2xl mb-3">
    Opencast failed workflows
</div>
@if(!empty($opencastWorkflows['failed']))
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Title
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Series
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                Date
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opencastWorkflows['failed'] as $workflow)
                            <tr class="bg-white border-b">
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $workflow['title'] }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                    {{ $workflow['series'] }}
                                </td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
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

