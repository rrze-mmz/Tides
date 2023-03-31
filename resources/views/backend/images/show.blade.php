@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 mb-5 font-semibold border-b border-black font-2xl items-center">
        <div class="flex">
            Image ID : {{ $image->id }} / {{ $image->description }}
        </div>
    </div>
    <div class="">
        <figure class="flex flex-wrap bg-slate-100 rounded-xl p-2 md:p-0 dark:bg-slate-800">
            <img class="w-24 h-24 md: rounded-[0.25rem] mr-10 "
                 src="{{ URL::asset('/images/'.$image->file_name) }}"
                 alt="{{ $image->description }}"
                 width="384" height="512">
            <div class=" flex flex-col pt-6 md:p-8 text-center space-y-4">
                <div class="flex">
                    <div class="px-4">
                        File name:
                    </div>
                    <div class="px-4">
                        {{ $image->file_name }}
                    </div>
                </div>
                <div class="flex">
                    <div class="px-4">
                        Description:
                    </div>
                    <div class="px-4">
                        {{ $image->description }}
                    </div>
                </div>
                <div class="flex">
                    <div class="px-4">
                        Size:
                    </div>
                    <div class="px-4">
                        {{ humanFileSizeFormat($image->file_size) }}
                    </div>
                </div>
                @foreach($mediaInfoContainer->getImages() as $imageInfo)
                    @if($imageInfo->has('width'))
                        <div class="flex">
                            <div class="px-4">
                                Width:
                            </div>
                            <div class="px-4">
                                {{ $imageInfo->get('width')->getAbsoluteValue() }} px
                            </div>
                        </div>
                    @endif
                    @if($imageInfo->has('height'))
                        <div class="flex">
                            <div class="px-4">
                                Height:
                            </div>
                            <div class="px-4">
                                {{ $imageInfo->get('height')->getAbsoluteValue() }} px
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </figure>
    </div>

    <div class="flex pt-10 border-b-2 border-black">
        <h3 class="font-bold">Used in following lecturers</h3>
    </div>
    <div class="flex">
        <div class="w-full mt-3 py-3">
            <ul>
                @foreach($image->presenters as $presenter)
                    <li class="w-full mt-2 mb-2 p-2">
                        {{  $presenter->getFullNameAttribute() }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
