@extends('layouts.frontend')

@section('content')

    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex flex-col justify-center content-center items-center place-content-center">
            <div class="text-2xl text-center pt-10">
                @yield('myPortalHeader')
            </div>
        </div>

        <div class="grid grid-cols-12 gap-2 pt-10">
            <div class="col-span-10 ">
                @yield('myPortalContent')
            </div>
            <div class="">
                @include('frontend.myPortal._sidebar')
            </div>
        </div>
    </main>

@endsection

