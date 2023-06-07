@extends('layouts.frontend')

@section('content')

    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <div class="pt-10 text-center text-2xl">
                @yield('myPortalHeader')
            </div>
        </div>

        <div class="grid grid-cols-12 gap-2 pt-10">
            <div class="col-span-10">
                @yield('myPortalContent')
            </div>
            <div class="">
                @include('frontend.myPortal._sidebar')
            </div>
        </div>
    </main>

@endsection

