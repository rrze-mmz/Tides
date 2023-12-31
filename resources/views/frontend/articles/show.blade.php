@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex items-center border-b-2 border-black pb-2">
            <div class="flex-grow">
                <h2 class="text-2xl font-bold"> {{ $article->title_de}}</h2>
            </div>
        </div>

        <div class="flex pt-10">
            <div class="">
                <article class=" mx-auto">
                    {!! $article->content_de !!}
                </article>
            </div>
        </div>
    </main>
@endsection
