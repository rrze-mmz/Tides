@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl">
        Create a device
    </div>
    <div class="flex p-2">
        <form action="{{ route('devices.store') }}"
              method="POST" class="w-4/5">
            @csrf

            <div class="flex flex-col gap-3">
                <x-form.input field-name="name"
                              input-type="text"
                              :value="old('name')"
                              label="Device name"
                              :full-col="true"
                              :required="true"
                />

                <x-form.select2-single field-name="location_id"
                                       label="Location"
                                       select-class="select2-tides"
                                       model="location"
                                       :selectedItem="1"
                />

                <x-form.select2-single field-name="organization_id"
                                       label="Organization"
                                       select-class="select2-tides-organization"
                                       model="organization"
                                       :selectedItem="(old('organization_id'))?? 1 "
                />

                <x-form.input field-name="opencast_device_name"
                              input-type="text"
                              :value="old('opencast_device_name')"
                              label="Opencast device name"
                              :full-col="true"
                              :required="false"
                />

                <x-form.input field-name="url"
                              input-type="url"
                              :value="old('url')"
                              label="URL"
                              :full-col="true"
                              :required="false"
                />

                <x-form.input field-name="room_url"
                              input-type="url"
                              :value="old('room_url')"
                              label="Room URL"
                              :full-col="true"
                              :required="false"
                />

                <x-form.input field-name="camera_url"
                              input-type="url"
                              :value="old('camera_url')"
                              label="Camera URL"
                              :full-col="true"
                              :required="false"
                />

                <x-form.input field-name="power_outlet_url"
                              input-type="url"
                              :value="old('power_outlet_url')"
                              label="Power outlet URL"
                              :full-col="true"
                              :required="false"
                />

                <x-form.input field-name="ip_address"
                              input-type="ip"
                              :value="old('ip_address','0.0.0.0')"
                              label="IP Address"
                              :full-col="true"
                              :required="false"
                />

                <x-form.toggle-button :value="false"
                                      label="Recording available"
                                      field-name="has_recording_func"
                />
                <x-form.toggle-button :value="false"
                                      label="Livestream available"
                                      field-name="has_livestream_func"
                />
                <x-form.toggle-button :value="false"
                                      label="Hybrid"
                                      field-name="is_hybrid"
                />
                <x-form.toggle-button :value="false"
                                      label="Operational"
                                      field-name="operational"
                />

                <x-form.textarea field-name="description"
                                 :value="old('description')"
                                 label="Description"
                />

                <x-form.textarea field-name="comment"
                                 :value="old('comment')"
                                 label="Comment"
                />

                <x-form.input field-name="telephone_number"
                              input-type="text"
                              :value="old('telephone_number','')"
                              label="Telephone number"
                              :full-col="true"
                              :required="false"
                />

                <div class="col-span-7 w-4/5 pt-10">
                    <x-form.button :link="$link=false"
                                   type="submit" t
                                   ext="Create device" />
                </div>
            </div>

        </form>
@endsection
