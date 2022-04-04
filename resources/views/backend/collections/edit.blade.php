@extends('layouts.backend')

@section('content')
    <div class="flex pb-2 font-semibold border-b border-black font-2xl">
        Edit Collection
    </div>

    <div class="flex py-2 px-2">
        <form action="{{ route('collections.update',$collection) }}"
              method="POST"
              class="w-4/5">
            @csrf
            @method('PATCH')
            <div class="flex flex-col gap-6">

                <x-form.input field-name="position"
                              input-type="number"
                              :value="$collection->position"
                              label="Position"
                              :full-col="false"
                              :required="false"
                />

                <x-form.input field-name="title"
                              input-type="text"
                              :value="$collection->title"
                              label="Title"
                              :full-col="true"
                              :required="true"
                />

                <x-form.textarea field-name="description"
                                 :value="$collection->description"
                                 label="Description"
                />

                <x-form.toggle-button :value="$collection->is_public"
                                      label="Public available"
                                      field-name="is_public"
                />
                <div class="col-span-7 w-4/5">
                    <x-form.button :link="$link=false"
                                   type="submit"
                                   text="Update collection"
                    />

                    <x-form.button :link="route('collections.index')"
                                   type="back"
                                   text="Back to collections index"
                                   color="green"
                    />
                </div>
            </div>
        </form>
    </div>
    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        Collection options
    </div>
    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        Toggle clips
    </div>
    <div class="flex pt-6">
        <form action="{{route('collections.toggleClips',$collection)}}"
              method="POST"
              class="w-4/5">
            @csrf
            <div class="flex flex-col gap-6">
                <x-form.select2-multiple field-name="ids"
                                         label="Clips"
                                         select-class="select2-tides-clips"
                                         :model="null"
                                         :items="[]"
                                         :full-col="true"
                                         :required="true"
                />
                <x-form.button :link="$link=false"
                               type="submit"
                               text="Toggle selected clips"
                />
            </div>
        </form>
    </div>
    <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
        Collection Clips
    </div>
    <div class="flex">
        <ul class="pt-3 w-full">
            <li class="flex content-center items-center p-5 mb-4 text-lg bg-gray-400 rounded text-center">
                <div class="pb-2 w-3/12 border-b border-black">Poster</div>
                <div class="pb-2 w-3/12 border-b border-black">Title</div>
                <div class="pb-2 w-2/12 border-b border-black">Access via</div>
                <div class="pb-2 w-2/12 border-b border-black">Semester</div>
                <div class="pb-2 w-1/12 border-b border-black">Duration</div>
                <div class="pb-2 w-1/12 border-b border-black">Actions</div>
            </li>
            @forelse($collection->clips as  $clip)
                <li class="flex  justify-center justify-items-center place-items-center p-5 mb-4 text-lg bg-gray-200 rounded text-center">
                    <div class="w-3/12">
                        <div class="flex mx-2 w-48 h-full">
                            <a href="{{$clip->adminPath()}}">
                                <img
                                    src="{{ fetchClipPoster($clip->posterImage) }}" alt="preview image">
                            </a>
                        </div>
                    </div>
                    <div class="w-3/12"> {{ $clip->title }}</div>
                    <div
                        class="w-2/12">{{ ($clip->acls->isEmpty())?'open':$clip->acls()->pluck('name')->implode(',') }}</div>
                    <div class="w-2/12">{{ $clip->semester->name }}</div>
                    <div class="w-1/12"> {{ $clip->assets->first()?->durationToHours()  }}</div>
                    <div class="w-1/12">
                        @if(Request::segment(1) === 'admin')
                            <x-form.button :link="$clip->adminPath()" type="submit" text="Edit"/>
                        @else
                            <form method="GET"
                                  action="{{$clip->Path() }}"
                            >
                                <button type="submit"
                                        class="focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-700
                                            hover:bg-blue-500 hover:shadow-lg"
                                >
                                    <x-heroicon-o-play class="w-6 h-6"/>
                                </button>
                            </form>
                        @endif
                    </div>
                </li>
            @empty
                <div class="grid place-items-center">
                    <div class=" w-full p-5 mb-4 text-2xl bg-gray-200 rounded text-center">
                        No clips
                    </div>
                </div>
            @endforelse
        </ul>
    </div>
@endsection
