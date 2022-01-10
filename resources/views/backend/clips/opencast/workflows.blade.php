<div class="flex pt-2 pb-2 font-semibold border-b border-black font-2xl">
    Opencast running events
</div>
@if($opencastSeriesInfo['running']->isNotEmpty() && $opencastSeriesInfo['running']['workflows']['totalCount']>0)
    <ul>
        @foreach($opencastSeriesInfo['running']['workflows']['workflow'] as $workflow)
            <li>
                {{ $workflow['mediapackage']['title'] }}
            </li>
        @endforeach
    </ul>
@else
    <div>No running workflows found</div>
@endif

@if($opencastSeriesInfo['failed']->isNotEmpty())
    <ul>
        @foreach($opencastSeriesInfo['failed'] as $event)
            <li>
                {{ $event['title']}}
            </li>
        @endforeach
    </ul>
@endif
