@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-screen md:mt-32">
        <div class="flex flex-col justify-center content-center items-center place-content-center">
            <h2 class="text-2xl font-bold">Clips index</h2>
        </div>

        <ul>
            @foreach($clips as $clip)
                <li>
                    <a href="{{ $clip->path() }}">{{ $clip->title }}</a>
                </li>
            @endforeach
        </ul>
    </main>
@endsection

