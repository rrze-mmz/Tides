@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-16 h-auto md:mt-32">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>

        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold dark:text-white">{{__('organization.index.Organization index')}}</h2>
        </div>
        <ul class="flex-row">
            <div class="grid grid-cols-3 items-stretch gap-4 pt-4">
                @forelse($organizations as $organization)
                    <a href="{{ route('frontend.organizations.show', $organization) }}"
                       class="m-2 rounded-lg border-2 border-solid border-black dark:border-white p-2 dark:hover:bg-slate-500 hover:bg-blue-200 h-full flex items-center justify-center">
                        <div class="text-center">
                            <h3 class="font-semibold dark:text-white">
                                {{ $organization->name }}
                            </h3>
                        </div>
                    </a>
                @empty
                    <li class="my-2 w-full rounded bg-white p-4">
                        {{ __('organization.index.No organizations found') }}
                    </li>
                @endforelse
            </div>
        </ul>
    </main>
@endsection
