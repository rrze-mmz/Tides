@if($opencastSeriesInfo['running']->isNotEmpty() && $opencastSeriesInfo['running']['workflows']['totalCount']>0)
    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        Opencast running events
    </div>
    <ul>
        @foreach($opencastSeriesInfo['running']['workflows']['workflow'] as $workflow)
            <li>
                {{ $workflow['mediapackage']['title'] }}
            </li>
        @endforeach
    </ul>
@endif

@if($opencastSeriesInfo['failed']->isNotEmpty())
    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        Opencast failed events
    </div>
    <ul>
        @foreach($opencastSeriesInfo['failed'] as $event)
            <li>
                {{ $event['title']}}
            </li>
        @endforeach
    </ul>
@endif
