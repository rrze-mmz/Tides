@php use Carbon\Carbon;use Illuminate\Support\Facades\URL; @endphp
@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-12 h-auto md:mt-12">
        <div class="pb-2">
            @include('frontend.search._searchbar')
        </div>
        <section class="dark:bg-gray-900">
            <div class="py-4 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6 ">
                <div class="mx-auto max-w-screen-lg text-center mb-8 lg:mb-16">
                    <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">
                        Podcasts von Studierenden, Lehrstühlen und der Uni-Leitung
                    </h2>
                    <p class="font-light text-gray-700 lg:mb-16 sm:text-xl dark:text-gray-400">
                        An der FAU gibt es eine ganze Reihe an Podcasts: Studierende stellen Wissenschaftlerinnen und
                        Wissenschaftler vor, Kanzler Christian Zens spricht über Entwicklungen an der Uni und einzelne
                        Lehrstühle präsentieren ihre Forschung.

                        Einen Überblick über das Podcast-Angebot der FAU finden Sie auf dieser Seite.
                    </p>
                </div>
                @include('layouts.breadcrumbs')
                <div class="grid gap-8 mb-6 lg:mb-16 md:grid-cols-2 grid-cols-2">
                    @forelse($podcasts as $podcast)
                        @include('partials.podcasts._card', $podcast)
                    @empty
                        <div class="dark:text-white font-bold text-4xl">
                            No podcasts found or published
                        </div>
                    @endforelse
                </div>
                <div>
                    <div class="py-10">
                        {{ $podcasts->links() }}
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

