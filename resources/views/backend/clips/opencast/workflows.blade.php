@if(isset($opencastWorkflows['running']) && $opencastWorkflows['running']['workflows']['totalCount']>0)
    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        Opencast running events
    </div>
    <ul>
        @foreach($opencastWorkflows['running']['workflows']['workflow'] as $workflow)
            <li>
                {{ $workflow['mediapackage']['title'] }}
            </li>
        @endforeach
    </ul>
@endif

@if($opencastWorkflows['failed']->isNotEmpty())
    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        Opencast failed events
    </div>
    <ul>
        @foreach($opencastWorkflows['failed'] as $workflow)
            <li>
                {{ $workflow['title']}}
            </li>
        @endforeach
    </ul>

@endif
