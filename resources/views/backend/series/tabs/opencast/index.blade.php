@if(!empty($opencastSeriesInfo->get('metadata')))
    @include('backend.series.tabs.opencast._metadata')
    @include('backend.series.tabs.opencast._editors')
    @include('backend.dashboard._opencast-workflows',[
                'opencastEvents' => $opencastSeriesInfo])
@else
    @include('backend.series.tabs.opencast._create-series-button')
@endif
