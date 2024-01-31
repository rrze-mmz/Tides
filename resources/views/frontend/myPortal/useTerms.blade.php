@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold dark:text-white">MyPortal</h2>
        </div>

        <form action="{{ route('frontend.acceptUseTerms') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex flex-col pt-10">
                <div class="text-2xl italic dark:text-white">
                    {{ __('myPortal.useTerms.use terms') }}
                </div>

                <div class="flex items-center pt-10">
                    <input name="accept_use_terms" type="checkbox" class="appearance-none checked:bg-blue-500" />
                    <label for="accept_use_terms" class="pl-4 font-bold dark:text-white">
                        {{ __('myPortal.useTerms.accept checkbox') }}
                    </label>

                </div>

                @error('accept_use_terms')
                <div class="col-start-2 col-end-6">
                    <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                </div>
                @enderror

                <div class="pt-10">
                    <x-button class="bg-green-600 hover:bg-green-700">
                        {{ __('myPortal.useTerms.button.Accept use terms') }}
                    </x-button>
                </div>
            </div>
        </form>

    </main>
@endsection
