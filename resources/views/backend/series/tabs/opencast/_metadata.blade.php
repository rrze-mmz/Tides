<div class="flex flex-col">
    <h4 class="mb-4 text-green-700">
        <span class="font-bold text-xl">Opencast Metadata</span> <span
            class="text-sm">created at {{ $opencastSeriesInfo['metadata']['created'] }}</span>
    </h4>
    <div>
        <span class="font-bold">Title</span>: {{$opencastSeriesInfo['metadata']['title']}}
    </div>
    <div>
            <span class="font-bold">
                Subject
            </span>: {{collect($opencastSeriesInfo['metadata']['subjects'])->implode(',')}}
    </div>
    <div>
            <span class="font-bold">
                Creator
            </span>: {{$opencastSeriesInfo['metadata']['creator']}}
    </div>
</div>
