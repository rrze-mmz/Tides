@if(method_exists($series, 'chapters') && $series->chapters()->count() > 0)
    @php
        $defaultChapter = ($series->chapters->filter(function($chapter){ return $chapter->default;})->first()?->id)??'0';
    @endphp
    @include('components.clips._chapters-lists',['$defaultChapter' => $defaultChapter, 'chapters'=>$chapters])
@else
    @include('components.clips._without-chapters-list',['clips' => $clips])
@endif

