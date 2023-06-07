<div>
    <x-button class="static bg-red-600 hover:bg-red-700"
              wire:click="$set('showModal',true)">
        Delete
        <x-heroicon-o-document-remove class="ml-2 h-4 w-4"/>
    </x-button>

    <x-modal wire:model.defer="showModal">
        <x-slot name="title">
            Are you sure you want to delete Image: {{ $model->description }} ?
        </x-slot>
        <x-slot name="body">
            <form
                wire:submit.prevent="delete"
                action="#"
                method="POST">
                @csrf
                <div>
                    <figure class="flex flex-wrap rounded-xl p-2 md:p-0">
                        <img class="mr-10 h-24 w-24 rounded-[0.25rem] md:"
                             src="{{ URL::asset('/images/'.$model->file_name) }}"
                             alt="{{ $model->description }}"
                             width="384" height="512">
                    </figure>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
            <x-button class="mr-4 bg-red-600 hover:bg-red-700"
                      wire:click="delete">
                Delete
            </x-button>
            <a href="#">
                <x-button wire:click="$set('showModal',false)"
                          class="bg-gray-400 hover:bg-gray-500">
                    Cancel
                </x-button>
            </a>
        </x-slot>
    </x-modal>
</div>
