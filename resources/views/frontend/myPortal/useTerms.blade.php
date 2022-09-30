@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-6 md:mt-12">
        <div class="flex flex-col justify-center content-center items-center place-content-center">
            <h2 class="text-2xl font-bold">MyPortal</h2>
        </div>

        <form action="{{ route('frontend.acceptUseTerms') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex flex-col pt-10 ">
                <div class="italic text-2xl">
                    {{{ trans('dashboard.user.use terms') }}}
                </div>

                <div class="pt-4 flex items-center">
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

                <div class="pt-4">
                    <x-form.button :link="$link=false"
                                   type="submit"
                                   text="Accept use terms"/>
                </div>
            </div>

        </form>

    </main>
@endsection
