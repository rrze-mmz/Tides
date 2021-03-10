@extends('layouts.backend')

@section('content')
    <main class="mx-auto lg:flex pt-12">
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

