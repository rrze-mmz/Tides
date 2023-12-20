<div class="flex flex-col font-normal">
    <h4 class="mb-4 text-green-700 dark:text-green-400">
        <span class="text-xl font-bold">Opencast Metadata</span> <span
            class="text-sm">created at {{ $opencastSeriesInfo['metadata']['created'] }}</span>
    </h4>
    <div class="dark:text-white">
        <span class="font-bold">Title</span>: {{$opencastSeriesInfo['metadata']['title']}}
    </div>
    <div class="dark:text-white">
            <span class="font-bold">
                Subject
            </span>: {{collect($opencastSeriesInfo['metadata']['subjects'])->implode(',')}}
    </div>
    <div class="dark:text-white">
            <span class="font-bold">
                Creator
            </span>: {{$opencastSeriesInfo['metadata']['creator']}}
    </div>
</div>
