@use(\Illuminate\Support\Str)
@extends('layouts.backend')

@section('content')
    @if($channels->empty())

        <div class="flex pt-10">
            <div class="mx-auto w-full">
                <ul>
                    <li class="">
                        <div
                            class="w-full px-8 py-6 text-left  bg-gray-200"
                        >
                            <div class="flex items-center justify-between">
                                    <span>
                                        Channel activation
                                    </span>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-slate-800">
                            <div class="p-2 bg-green-100 dark:bg-green-700 my-2">
                                {{{ __('myPortal.adminPortalUseTerms.Admin portal use terms') }}}
                            </div>
                            <div class="p-4">
                                <x-form.input field-name="url_handle"
                                              input-type="text"
                                              :value="'@'.Str::before(auth()->user()->email,'@')"
                                              :disabled="true"
                                              label="Url handle"
                                              :fullCol="true"
                                              :required="true" />
                            </div>
                            <div class="p-4">
                                <x-form.input field-name="name"
                                              input-type="text"
                                              :value="'My channel'"
                                              label="Name"
                                              :fullCol="true"
                                              :required="true" />
                            </div>
                            <div class="p-4">
                                <x-form.textarea field-name="description"
                                                 :value="''"
                                                 label="Description" />
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    @else
        <div class="mb-5 flex items-center justify-between border-b border-black pb-2 font-semibold font-2xl
                dark:text-white dark:border-white"
        >
            <div class="flex">
                Your channels
            </div>
        </div>
    @endif

@endsection
