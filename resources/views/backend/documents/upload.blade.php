@php
    $resource = $resource ?? null ;
@endphp
@if($resource)
    <div class="mx-4 h-full w-full rounded border bg-white px-4 py-4  dark:bg-gray-800
           dark:border-blue-800 dark:text-white  font-normal">
        <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 py-4 pl-4 text-xl">
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
                <input type="hidden" name="type" value="{{ str(class_basename($resource))->lower() }}" />
                <input type="hidden" name="id" value="{{ $resource?->id }}">
            </div>
            @error('document')
            <div class="flex pb-6">
                <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
            </div>
            @enderror

            <x-button class="bg-green-600 hover:bg-green-700">
                {{ __('common.actions.upload') }}
            </x-button>
        </form>

        @if($resource->documents()->count()> 0)
            <h4 class="border-b-2 pt-6 pb-2">
                {{ class_basename($resource) }} {{trans_choice('common.menu.document', 2)}}
            </h4>
            <div class="flex py-6 pl-4">
                <ul class="list-disc">
                    @foreach($resource->documents()->get() as $document)
                        <li class="py-4">
                            {{ str($document->name)->limit(30,'...')}}
                            <div class="flex text-xl space-x-4 pt-4"
                            >
                                <a data-message="view-document"
                                   href="{{ route('document.'.str(class_basename($resource))->lower().'.view',[$resource, $document]) }}">
                                    <x-button class="bg-green-600 hover:bg-green-700">
                                        Anschauen
                                    </x-button>
                                </a>
                                <div>
                                    <x-modals.delete
                                            :route="route('documents.destroy', $document)"
                                            class="w-full justify-center"
                                    >
                                        <x-slot:title>
                                            {{ __('article.backend.delete.modal title',[
                                            'document_name'=>$document->name
                                            ]) }}
                                        </x-slot:title>
                                        <x-slot:body>
                                            {{ __('document.backend.delete.modal body') }}
                                        </x-slot:body>
                                    </x-modals.delete>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif

