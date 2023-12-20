@use(App\Enums\ApplicationStatus)

@extends('layouts.myPortal')

@section('myPortalHeader')
    {{ __('myPortal.applications.myPortal applications') }}
@endsection

@section('myPortalContent')
    <div class="pr-5">
        @if($settings['accept_admin_portal_use_terms'])
            <div class="mt-5 flex w-full rounded-3xl bg-gray-100 p-4">
                <h4 class="font-extrabold">
                    {{ __('myPortal.applications.Application status') }} :
                    <span class="pl-4 text-sky-600">
                    {{ ApplicationStatus::tryFrom($settings['admin_portal_application_status']) }}
                </span>

                </h4>
            </div>
        @endif

        <div class="flex pt-10">
            <div class="mx-auto w-full border border-gray-200 bg-white"
                 x-data="{selected:0}">
                <ul class="shadow-box">
                    <li class="relative border-b border-gray-200">
                        <button type="button"
                                class="w-full px-8 py-6 text-left"
                                @click="selected !== 1 ? selected = 1 : selected = null"
                        >
                            <div class="flex items-center justify-between">
                                    <span>
                                        {{__('myPortal.applications.Accepted Admin Portal Use Terms')}}
                                    </span>
                                <x-heroicon-o-plus-circle class="h-6 w-6" />
                            </div>
                        </button>
                        <div class="relative max-h-0 overflow-hidden transition-all duration-700" style=""
                             x-ref="container1"
                             x-bind:style="selected == 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
                            <div class="p-6">
                                {{{ __('myPortal.adminPortalUseTerms.Admin portal use terms') }}}
                            </div>
                        </div>
                    </li>


                    <li class="relative border-b border-gray-200">
                        <button type="button"
                                class="w-full px-8 py-6 text-left"
                                @click="selected !== 2 ? selected = 2 : selected = null"
                        >
                            <div class="flex items-center justify-between">
                                <span>
                                    {{ __('myPortal.applications.Accepted Portal Use Terms') }}
                                </span>
                                <x-heroicon-o-plus-circle class="h-6 w-6" />
                            </div>
                        </button>
                        <div class="relative max-h-0 overflow-hidden transition-all duration-700"
                             style=""
                             x-ref="container2"
                             x-bind:style="selected == 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''">
                            <div class="p-6">
                                {{ __('myPortal.useTerms.use terms')  }}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection
