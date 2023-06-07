@extends('layouts.backend')

@section('content')
    <div class="mb-5 flex items-center border-b border-black pb-2 font-semibold font-2xl">
        <div class="flex">
            Image ID : {{ $image->id }} / {{ $image->description }}
        </div>
    </div>
    <div class="">
        <figure class="flex flex-wrap rounded-xl bg-slate-100 p-2 dark:bg-slate-800 md:p-0">
            <img class="mr-10 h-24 w-24 rounded-[0.25rem] md:"
                 src="{{ URL::asset('/images/'.$image->file_name) }}"
                 alt="{{ $image->description }}"
                 width="384" height="512">
            <div class="flex flex-col pt-6 text-center space-y-4 md:p-8">
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

    <div class="flex border-b-2 border-black pt-10">
        <h3 class="font-bold">Used in following lecturers</h3>
    </div>
    <div class="flex">
        <div class="mt-3 w-full py-3">
            <ul>
                @foreach($image->presenters as $presenter)
                    <li class="mt-2 mb-2 w-full p-2">
                        {{  $presenter->getFullNameAttribute() }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
