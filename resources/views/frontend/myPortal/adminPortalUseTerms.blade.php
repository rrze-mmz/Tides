@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex flex-col justify-center content-center items-center place-content-center">
            <h2 class="text-2xl font-bold">Access to admin Portal</h2>
        </div>

        <form action="{{ route('frontend.admin.portal.accept.use.terms') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex flex-col pt-10 ">
                <div class="italic text-2xl">
                    {{{ trans('dashboard.user.admin portal use terms') }}}
                </div>

                <div class="pt-10 flex items-center">
                    <input name="accept_use_terms" type="checkbox" class="appearance-none checked:bg-blue-500 "/>
                    <label for="accept_use_terms" class="pl-4 font-bold">
                        {{ trans('dashboard.user.accept checkbox') }}
                    </label>

                </div>

                @error('accept_use_terms')
                <div class="col-start-2 col-end-6">
                    <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                </div>
                @enderror

                <div class="pt-10">
                    <x-button class="bg-green-600 hover:bg-green-700">
                        Accept use terms
                    </x-button>
                </div>
            </div>
        </form>

    </main>
@endsection
