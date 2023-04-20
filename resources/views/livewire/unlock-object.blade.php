<div>
    <x-button class="static bg-blue-400 hover:bg-blue-500 "
              wire:click="$set('showModal',true)">
        Unlock series
        <x-heroicon-o-lock-open class="ml-2 w-4 h-4"/>
    </x-button>

    <x-modal wire:model.defer="showModal">
        <x-slot name="title">
            Unlock series:{{ str($model->title)->limit(50,'...') }}
        </x-slot>
        <x-slot name="body">
            <form
                wire:submit.prevent="unlock"
                action="#"
                method="POST">
                @csrf

                <label for="password" class="flex justify-center mx-auto">
                    <input class="rounded-md my-4" type="password" name="password" wire:model="password">
                </label>

                @error('password')
                <div class="flex justify-center mx-auto my-auto text-red-700">
                    <span class="error">{{ $message }}</span>
                </div>
                @enderror
            </form>
        </x-slot>
        <x-slot name="footer">
            <x-button class="bg-blue-400 hover:bg-blue-500 mr-4"
                      wire:click="unlock"
            >
                Unlock
            </x-button>
            <a href="#">
                <x-button wire:click="$set('showModal',false)"
                          class="bg-gray-400 hover:bg-gray-500"
                >
                    Cancel
                </x-button>
            </a>
        </x-slot>
    </x-modal>
</div>
