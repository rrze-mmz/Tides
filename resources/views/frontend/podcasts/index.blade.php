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
                        <div
                            class="items-center bg-gray-50 rounded-lg shadow sm:flex dark:bg-gray-800 dark:border-gray-700">
                            <a href="{{ route('frontend.podcasts.show', $podcast) }}">
                                <img class="max-w-fit w-48 rounded-lg sm:rounded-none sm:rounded-l-lg px-2"
                                     @if(!is_null($podcast->image_id))
                                         src="{{ asset('images/'.$podcast->cover->file_name) }}"
                                     @else
                                         src="/podcast-files/covers/PodcastDefaultFAU.png"
                                     @endif
                                     alt="{{ $podcast->title }} cover image">
                            </a>
                            <div class="p-4">
                                <h3 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    <a href="{{ route('frontend.podcasts.show', $podcast) }}">{{ $podcast->title }}</a>
                                </h3>
                                <div class="">
                                    <p class="mt-3 mb-4 font-light text-gray-500 dark:text-white">
                                        @if($podcast->description==='')
                                            <span class="italic">No description available</span>
                                        @else
                                            {{ Str::limit(removeHtmlElements($podcast->description), 250, ' (...)') }}
                                        @endif
                                    </p>
                                </div>

                                <ul class="flex space-x-4 sm:mt-0">
                                    <li>
                                        <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                                            <svg fill="#000000" czlass="w-5 h-5 "
                                                 id="Capa_1"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 viewBox="0 0 186.845 186.845"
                                                 xml:space="preserve">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                            </g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                   stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <g>
                                                        <path
                                                            d="M128.875,120.962c-31.094-14.37-74.616-8.014-76.453-7.737c-4.096,0.619-6.915,4.44-6.296,8.536 c0.619,4.096,4.443,6.912,8.536,6.296c0.406-0.062,40.867-5.982,67.92,6.521c1.018,0.471,2.089,0.694,3.142,0.694 c2.834-0.001,5.546-1.614,6.813-4.355C134.274,127.157,132.635,122.7,128.875,120.962z"></path>
                                                        <path
                                                            d="M137.614,93.953c-35.313-16.319-84.833-9.087-86.924-8.772c-4.094,0.619-6.911,4.438-6.294,8.532 c0.616,4.095,4.438,6.916,8.531,6.301c0.468-0.071,47.206-6.857,78.394,7.556c1.02,0.471,2.089,0.694,3.142,0.694 c2.834-0.001,5.546-1.614,6.814-4.356C143.014,100.148,141.374,95.691,137.614,93.953z"></path>
                                                        <path
                                                            d="M143.49,65.736c-39.006-18.027-93.79-10.028-96.103-9.679c-4.094,0.619-6.911,4.438-6.294,8.532s4.44,6.919,8.531,6.3 c0.523-0.079,52.691-7.657,87.573,8.463c1.018,0.471,2.089,0.694,3.142,0.694c2.834,0,5.546-1.614,6.813-4.355 C148.89,71.93,147.25,67.474,143.49,65.736z"></path>
                                                        <path
                                                            d="M93.423,0.001C41.909,0.001,0,41.909,0,93.42c0,51.514,41.909,93.424,93.423,93.424c51.513,0,93.422-41.91,93.422-93.424 C186.845,41.909,144.936,0.001,93.423,0.001z M93.423,171.844C50.18,171.844,15,136.664,15,93.42 c0-43.241,35.18-78.42,78.423-78.42c43.242,0,78.422,35.179,78.422,78.42C171.845,136.664,136.665,171.844,93.423,171.844z">
                                                        </path>
                                                    </g>
                                                </g>
                                        </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                                            <svg class="w-5 h-5" fill="#000000" viewBox="0 0 32 32"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                   stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path
                                                        d="M7.12 0c-3.937-0.011-7.131 3.183-7.12 7.12v17.76c-0.011 3.937 3.183 7.131 7.12 7.12h17.76c3.937 0.011 7.131-3.183 7.12-7.12v-17.76c0.011-3.937-3.183-7.131-7.12-7.12zM15.817 3.421c3.115 0 5.932 1.204 8.079 3.453 1.631 1.693 2.547 3.489 3.016 5.855 0.161 0.787 0.161 2.932 0.009 3.817-0.5 2.817-2.041 5.339-4.317 7.063-0.812 0.615-2.797 1.683-3.115 1.683-0.12 0-0.129-0.12-0.077-0.615 0.099-0.792 0.192-0.953 0.64-1.141 0.713-0.296 1.932-1.167 2.677-1.911 1.301-1.303 2.229-2.932 2.677-4.719 0.281-1.1 0.244-3.543-0.063-4.672-0.969-3.595-3.907-6.385-7.5-7.136-1.041-0.213-2.943-0.213-4 0-3.636 0.751-6.647 3.683-7.563 7.371-0.245 1.004-0.245 3.448 0 4.448 0.609 2.443 2.188 4.681 4.255 6.015 0.407 0.271 0.896 0.547 1.1 0.631 0.447 0.192 0.547 0.355 0.629 1.14 0.052 0.485 0.041 0.62-0.072 0.62-0.073 0-0.62-0.235-1.199-0.511l-0.052-0.041c-3.297-1.62-5.407-4.364-6.177-8.016-0.187-0.943-0.224-3.187-0.036-4.052 0.479-2.323 1.396-4.135 2.921-5.739 2.199-2.319 5.027-3.543 8.172-3.543zM16 7.172c0.541 0.005 1.068 0.052 1.473 0.14 3.715 0.828 6.344 4.543 5.833 8.229-0.203 1.489-0.713 2.709-1.619 3.844-0.448 0.573-1.537 1.532-1.729 1.532-0.032 0-0.063-0.365-0.063-0.803v-0.808l0.552-0.661c2.093-2.505 1.943-6.005-0.339-8.296-0.885-0.896-1.912-1.423-3.235-1.661-0.853-0.161-1.031-0.161-1.927-0.011-1.364 0.219-2.417 0.744-3.355 1.672-2.291 2.271-2.443 5.791-0.348 8.296l0.552 0.661v0.813c0 0.448-0.037 0.807-0.084 0.807-0.036 0-0.349-0.213-0.683-0.479l-0.047-0.016c-1.109-0.885-2.088-2.453-2.495-3.995-0.244-0.932-0.244-2.697 0.011-3.625 0.672-2.505 2.521-4.448 5.079-5.359 0.547-0.193 1.509-0.297 2.416-0.281zM15.823 11.156c0.417 0 0.828 0.084 1.131 0.24 0.645 0.339 1.183 0.989 1.385 1.677 0.62 2.104-1.609 3.948-3.631 3.005h-0.015c-0.953-0.443-1.464-1.276-1.475-2.36 0-0.979 0.541-1.828 1.484-2.328 0.297-0.156 0.709-0.235 1.125-0.235zM15.812 17.464c1.319-0.005 2.271 0.463 2.625 1.291 0.265 0.62 0.167 2.573-0.292 5.735-0.307 2.208-0.479 2.765-0.905 3.141-0.589 0.52-1.417 0.667-2.209 0.385h-0.004c-0.953-0.344-1.157-0.808-1.553-3.527-0.452-3.161-0.552-5.115-0.285-5.735 0.348-0.823 1.296-1.285 2.624-1.291z"></path>
                                                </g>
                                            </svg>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                                            <svg class="w-5 h-5" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 fill="none"
                                                 viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961" />
                                            </svg>

                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
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

