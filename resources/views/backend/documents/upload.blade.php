@php
    $resource = $resource ?? null ;
@endphp
@if($resource)
    <div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
        <h2 class="text-xl font-normal py-4 -ml-5 mb-3 border-l-4 border-blue-600 pl-4 ">
            {{ __('common.forms.Upload a document') }}
        </h2>

        <form
            method="POST"
            class="px-2"
            enctype="multipart/form-data"
            action="{{ route('documents.upload') }}"
        >
            @csrf

            <div class="flex pb-6">
                <input type="file" name="document" accept="application/pdf,  application/doc, application/docx">
                <input type="hidden" name="type" value="{{ str(class_basename($resource))->lower() }}"/>
                <input type="hidden" name="id" value="{{ $resource?->id }}">
            </div>
            @error('document')
            <div class="flex pb-6">
                <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
            </div>
            @enderror

            <x-form.button :link="$link=false" type="submit" text="Upload file"/>
        </form>

        @if($resource->documents()->count()> 0)
            <h4 class="pt-6 border-b-2 pb-2">
                {{ class_basename($resource) }} {{trans_choice('common.menu.document', 2)}}
            </h4>
            <div class="flex py-6 pl-4">
                <ul class="list-disc">
                    @foreach($resource->documents()->get() as $document)
                        <li>
                            {{ str($document->name)->limit(30,'...')}}
                            <div class="flex"
                            >
                                <a data-message="view-document"
                                   href="{{ route('document.'.str(class_basename($resource))->lower().'.view',[$resource, $document]) }}">
                                    <x-heroicon-o-eye class="w-6 h-6"/>
                                </a>
                                <div>
                                    <form action="{{route('documents.destroy',$document)}}"
                                          method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" data-message="delete-document">
                                            <x-heroicon-o-x-circle class="w-6 h-6"/>
                                        </button>

                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif

