@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-12 h-auto md:mt-12">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>

        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold dark:text-white">{{ __('series.frontend.index.Series index') }}</h2>
        </div>

        <livewire:index-pages-datatable />
    </main>
@endsection
