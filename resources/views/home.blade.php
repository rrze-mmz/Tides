@extends('layouts.app')

@section('content')
<main class="sm:container sm:mx-auto sm:mt-10">
       <div class="flex">
            @forelse($latestClips as $clip)
                    <div>
                        <h2>{{ $clip->title }}</h2>
                    </div>
           @empty
                No clips found
           @endforelse
       </div>
</main>
@endsection
