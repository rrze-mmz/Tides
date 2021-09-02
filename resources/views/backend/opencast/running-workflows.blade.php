<div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
    Opencast running events
</div>
<ul>
    @foreach($opencastSeriesRunningWorkflows['workflows']['workflow'] as $workflow)
        <li>
            {{ $workflow['mediapackage']['title'] }}
        </li>
    @endforeach
</ul>
