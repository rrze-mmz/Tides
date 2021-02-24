@foreach($clips as $clip)
    {{ $clip->title }}

    {{ $clip->description }}
@endforeach
