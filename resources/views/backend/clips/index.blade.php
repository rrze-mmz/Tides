@extends('layouts.backend')

@section('content')
    <main class="pt-12 mx-auto lg:flex">
        <h2>Clips index</h2>
        <ul>
            @foreach($clips as $clip)
                <li>
                    <a href="{{ $clip->path() }}">{{ $clip->title }}</a>
                </li>
            @endforeach
        </ul>
    </main>
@endsection

