@extends('layouts.backend')

@section('content')
    <div class="flex justify-between pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Edit Image ID : {{ $image->id }} / {{ $image->description }}
        </div>
        <div class="flex">
            <a href="{{ route('images.index') }}">
                <x-button class="bg-blue-700 hover:bg-blue-700 flex items-center">
                    <div class="pr-2">
                        <x-heroicon-o-arrow-circle-left class="w-6 h-6"/>
                    </div>
                    <div>
                        Back to images list
                    </div>
                </x-button>
            </a>
        </div>
    </div>
    <div class="flex">
        <figure class="flex bg-slate-100 rounded-xl md:p-0 w-full">
            <div class="grow justify-center content-center content-between py-2 px-2">
                <form action="{{ route('images.update', $image) }}"
                      method="POST"
                      class="w-full"
                >
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-col gap-3">
                        <x-form.input field-name="file_name"
                                      input-type="text"
                                      :disabled="true"
                                      :value="$image->file_name"
                                      label="{{__('common.forms.file name')}}"
                                      :full-col="true"
                                      :required="true"
                        />

                        <x-form.input field-name="description"
                                      input-type="description"
                                      :value="$image->description"
                                      label="{{__('common.forms.description')}}"
                                      :full-col="true"
                                      :required="true"
                        />
                        <x-form.input field-name="mime_type"
                                      input-type="mime_type"
                                      disabled
                                      :value=" ($image->mime_type)"
                                      label="{{__('common.forms.mime type')}}"
                                      :full-col="true"
                                      :required="true"
                        />
                        <x-form.input field-name="size"
                                      input-type="size"
                                      disabled
                                      :value=" humanFileSizeFormat($image->file_size)"
                                      label="{{__('common.forms.size')}}"
                                      :full-col="true"
                                      :required="true"
                        />
                        @foreach($mediaInfoContainer->getImages() as $imageInfo)
                            @if($imageInfo->has('width'))
                                <x-form.input field-name="width"
                                              input-type="width"
                                              :disabled="true"
                                              :value=" $imageInfo->get('width')->getAbsoluteValue(). 'px'"
                                              label="{{__('common.forms.width')}}"
                                              :full-col="true"
                                              :required="true"
                                />

                            @endif
                            @if($imageInfo->has('height'))
                                <x-form.input field-name="height"
                                              input-type="height"
                                              disabled
                                              :value=" $imageInfo->get('height')->getAbsoluteValue(). 'px'"
                                              label="{{__('common.forms.height')}}"
                                              :full-col="true"
                                              :required="true"
                                />
                            @endif
                        @endforeach
                    </div>
                    <div class="pt-10">
                        <x-button class="bg-blue-600 hover:bg-blue-700">
                            {{__('common.actions.update')}}
                        </x-button>
                    </div>
                </form>
            </div>
            <div class="flex-none flex-col mr-10">
                <div>
                    <img class="object-contain h-96 w-48 w-auto md: rounded-[0.25rem]"
                         src="{{ URL::asset('/images/'.$image->file_name) }}"
                         alt="{{ $image->description }}">
                </div>
                <div class="flex">
                    <div class="pt-4 w-full">
                        <form action="{{ route('images.import', $image) }}"
                              method="POST"
                              class="w-full"
                        >
                            @csrf

                            <input type="file"
                                   name="image"
                                   class="filepond "
                                   data-max-file-size="10MB"
                            />
                            @error('image')
                            <div class="col-start-2 col-end-6">
                                <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                            </div>
                            @enderror
                            <x-button class="bg-blue-600 hover:bg-blue-700">
                                Replace current image
                            </x-button>

                        </form>
                    </div>
                </div>
            </div>
        </figure>
    </div>

    @if($image->presenters()->count() > 0)
        <div class="flex items-center pt-10 border-b-2 border-black">
            <div class="pr-2">
                <x-heroicon-o-user-group class="w-6 h-6"/>
            </div>
            <h3 class="font-bold">Used in {{$image->presenters()->count()}} lecturers
            </h3>
        </div>
        <div class="flex">
            <div class="w-full mt-3 py-3">
                <ul>
                    @foreach($image->presenters as $presenter)
                        <li class="w-full mt-2 mb-2 p-2">
                            <div class="flex items-center align-middle">
                                <div>
                                    {{  $presenter->getFullNameAttribute() }}
                                </div>
                                <div class="pl-2">
                                    <a href="{{ route('presenters.edit', $presenter) }}">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    @if($image->series()->count() > 0)
        <div class="flex items-center pt-10 border-b-2 border-black">
            <div class="pr-2">
                <x-heroicon-o-user-group class="w-6 h-6"/>
            </div>
            <h3 class="font-bold">Used in {{$image->series()->count()}} series
            </h3>
        </div>
        <div class="flex">
            <div class="w-full mt-3 py-3">
                <ul>
                    @foreach($image->series->take(5) as $clip)
                        <li class="w-full mt-2 mb-2 p-2">
                            <div class="flex items-center align-middle">
                                <div>
                                    {{  $clip->title }}
                                </div>
                                <div class="pl-2">
                                    <a href="{{ route('series.edit', $clip) }}">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    @if($image->clips()->count() > 0)
        <div class="flex items-center pt-10 border-b-2 border-black">
            <div class="pr-2">
                <x-heroicon-o-user-group class="w-6 h-6"/>
            </div>
            <h3 class="font-bold">Used in {{$image->clips()->count()}} clips
            </h3>
        </div>
        <div class="flex">
            <div class="w-full mt-3 py-3">
                <ul>
                    @foreach($image->clips->take(5) as $clip)
                        <li class="w-full mt-2 mb-2 p-2">
                            <div class="flex items-center align-middle">
                                <div>
                                    {{  $clip->title }}
                                </div>
                                <div class="pl-2">
                                    <a href="{{ route('clips.edit', $clip) }}">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endsection
