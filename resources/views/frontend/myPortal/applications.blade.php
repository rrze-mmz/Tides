@php use App\Enums\ApplicationStatus; @endphp
@extends('layouts.myPortal')

@section('myPortalHeader')
    myPortal Applications
@endsection

@section('myPortalContent')
    <div class="pr-5">
        @if($settings['accept_admin_portal_use_terms'])
            <div class="flex w-full bg-gray-100 p-4 mt-5 rounded-3xl">
                <h4 class=" font-extrabold ">
                    Application status:
                    <span class=" pl-4 text-sky-600">
                    {{ ApplicationStatus::tryFrom($settings['admin_portal_application_status']) }}
                </span>

                </h4>
            </div>
        @endif

        <div class="flex pt-10">
            <div class="bg-white w-full mx-auto border border-gray-200" x-data="{selected:0}">
                <ul class="shadow-box">

                    <li class="relative border-b border-gray-200">

                        <button type="button" class="w-full px-8 py-6 text-left"
                                @click="selected !== 1 ? selected = 1 : selected = null">
                            <div class="flex items-center justify-between">
					<span>
						Admin Portal Use Terms
                    </span>
                                <x-heroicon-o-plus-circle class="w-6 h-6"/>
                            </div>
                        </button>

                        <div class="relative overflow-hidden transition-all max-h-0 duration-700" style=""
                             x-ref="container1"
                             x-bind:style="selected == 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
                            <div class="p-6">
                                {{{ trans('dashboard.user.admin portal use terms') }}}
                            </div>
                        </div>

                    </li>


                    <li class="relative border-b border-gray-200">

                        <button type="button" class="w-full px-8 py-6 text-left"
                                @click="selected !== 2 ? selected = 2 : selected = null">
                            <div class="flex items-center justify-between">
					<span>
                        Portal Use Terms
                    </span>
                                <x-heroicon-o-plus-circle class="w-6 h-6"/>
                            </div>
                        </button>

                        <div class="relative overflow-hidden transition-all max-h-0 duration-700" style=""
                             x-ref="container2"
                             x-bind:style="selected == 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''">
                            <div class="p-6">
                                {{ trans('dashboard.user.use terms') }}
                            </div>
                        </div>

                    </li>

                </ul>
            </div>
        </div>
    </div>

@endsection
