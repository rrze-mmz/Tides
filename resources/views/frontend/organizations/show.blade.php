@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-auto md:mt-32">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>

        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold">
                {{ __('organization.show.Organization Series index', ['orgName' => $organization->name]) }}
            </h2>
        </div>
        <ul class="flex-row">
            <div class="grid grid-cols-4 gap-4">
                @forelse($orgSeries as $singleSeries)
                    <li class="my-2 w-full rounded bg-white p-4">
                        @include('backend.series._card',['series'=> $singleSeries])
                    </li>
                @empty
                    <li class="my-2 w-full rounded bg-white p-4">
                        {{ __('organization.show.Organization no series found', ['orgName' => $organization->name]) }}
                    </li>
                @endforelse
            </div>

            <div class="py-10">
                {{ $orgSeries->links() }}
            </div>
        </ul>
    </main>
@endsection
