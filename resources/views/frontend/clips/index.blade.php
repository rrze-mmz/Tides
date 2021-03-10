@extends('layouts.frontend')

@section('content')
    <main class="container  mx-auto  mt-32 md:mt-32 h-screen">
        <div class="flex flex-col content-center justify-center place-content-center items-center">
            <h2 class="font-bold text-2xl">Clips index</h2>
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

