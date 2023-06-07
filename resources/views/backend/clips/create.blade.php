@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl">
        Creates new clip
    </div>
    <div class="flex px-2 py-2">
        <form action="{{ route('clips.store')}}"
              method="POST"
              class="w-4/5">
            @csrf

            <div class="flex flex-col gap-3">

                <x-form.input field-name="episode"
                              input-type="number"
                              value="1"
                              label="Episode"
                              :full-col="false"
                              :required="false"
                />
                <x-form.datepicker field-name="recording_date"
                                   label="Recording Date"
                                   :full-col="false"
                                   :value="now()"/>

                <x-form.input field-name="title"
                              input-type="text"
                              :value="old('title')"
                              label="Title"
                              :full-col="true"
                              :required="true"
                />

                <x-form.textarea field-name="description"
                                 :value="old('description')"
                                 label="Description"
                />

                <x-form.select2-single field-name="organization_id"
                                       label="Organization"
                                       select-class="select2-tides-organization"
                                       model="organization"
                                       :selectedItem="(old('organization_id'))?? 1 "
                />

                <x-form.select2-single field-name="language_id"
                                       label="Language"
                                       select-class="select2-tides"
                                       model="language"
                                       :selectedItem="1"
                />

                <div class="mb-2 border-b border-solid border-b-black pb-2 text-left text-xl font-bold">
                    Metadata
                </div>

                <x-form.select2-single field-name="context_id"
                                       label="Context"
                                       select-class="select2-tides"
                                       model="context"
                                       :selectedItem="1"
                />

                <x-form.select2-single field-name="format_id"
                                       label="Format"
                                       select-class="select2-tides"
                                       model="format"
                                       :selectedItem="1"
                />

                <x-form.select2-single field-name="type_id"
                                       label="Type"
                                       select-class="select2-tides"
                                       model="type"
                                       :selectedItem="1"
                />

                <x-form.select2-multiple field-name="presenters"
                                         label="Presenters"
                                         select-class="select2-tides-presenters"
                                         :model="null"
                                         :items="[]"
                />

                <x-form.select2-single field-name="semester_id"
                                       label="Semester"
                                       select-class="select2-tides"
                                       model="semester"
                                       :selectedItem="old('semester_id')"
                />

                <x-form.select2-multiple field-name="tags"
                                         label="Tags"
                                         select-class="select2-tides-tags"
                                         :model="null"
                                         :items="[]"
                />

                <div class="mb-2 border-b border-solid border-b-black pb-2 text-left text-xl font-bold">
                    Access
                </div>

                <x-form.select2-multiple field-name="acls"
                                         label="Accessible via"
                                         :model="null"
                                         select-class="select2-tides"
                />

                <x-form.password field-name="password"
                                 :value="old('password')"
                                 label="Password"
                                 :full-col="true"
                />

                <x-form.toggle-button :value="true"
                                      label="Allow comments"
                                      field-name="allow_comments"
                />

                <x-form.toggle-button :value="true"
                                      label="Public available"
                                      field-name="is_public"
                />


                <div class="col-span-7 w-4/5 pt-8">
                    <x-button class="bg-blue-600 hover:bg-blue-700">
                        Create clip
                    </x-button>
                </div>
            </div>
        </form>
    </div>
    <script>
        const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];


        function app() {

            return {

                showDatepicker: false,

                datepickerValue: '',


                month: '',

                year: '',

                no_of_days: [],

                blankdays: [],

                days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],


                initDate() {

                    let today = new Date();

                    this.month = today.getMonth();

                    this.year = today.getFullYear();

                    this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();

                },

                isToday(date) {

                    const today = new Date();

                    const d = new Date(this.year, this.month, date);


                    return today.toDateString() === d.toDateString() ? true : false;

                },

                getDateValue(date) {

                    let selectedDate = new Date(this.year, this.month, date);

                    this.datepickerValue = selectedDate.toDateString();

                    this.$refs.date.value = selectedDate.getFullYear() + "-" + ('0' + selectedDate.getMonth()).slice(-2) + "-" + ('0' + selectedDate.getDate()).slice(-2);

                    console.log(this.$refs.date.value);


                    this.showDatepicker = false;

                },


                getNoOfDays() {

                    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();


                    // find where to start calendar day of week

                    let dayOfWeek = new Date(this.year, this.month).getDay();

                    let blankdaysArray = [];

                    for (var i = 1; i <= dayOfWeek; i++) {

                        blankdaysArray.push(i);

                    }


                    let daysArray = [];

                    for (var i = 1; i <= daysInMonth; i++) {

                        daysArray.push(i);

                    }


                    this.blankdays = blankdaysArray;

                    this.no_of_days = daysArray;

                }

            }

        }
    </script>
@endsection
