@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-32 h-auto md:mt-32">
        <div class="pb-10">
            @include('frontend.search._searchbar')
        </div>

        <div class="flex flex-col place-content-center content-center items-center justify-center py-12">
            <h2 class="text-2xl font-bold dark:text-white">
                {{ __('organization.show.Organization Series index', ['orgName' => $organization->name]) }}
            </h2>
        </div>
        <livewire:index-pages-datatable :type="'organization'" :organization="$organization" />
    </main>
@endsection
