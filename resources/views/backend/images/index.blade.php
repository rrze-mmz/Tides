@extends('layouts.backend')

@section('content')
    <div class="flex">
        <div>
            <a href="{{ route('images.create') }}">
                <x-button class="bg-blue-600 hover:bg-blue-700">
                    create a new image
                </x-button>
            </a>
        </div>
    </div>
    @forelse($images as $image)
        <h2>
            {{ $image->file_name }}
        </h2>

    @empty
        <div>
            <h1>No images found. Please create one </h1>
        </div>
    @endforelse
@endsection
