<div>
    @if ($messageText)
        <x-message
            :messageText="$messageText"
            :messageType="$messageType" />
    @endif

    <form wire:submit="submitForm" action="#"
          class="flex flex-col"
    >
        @csrf

        <div class="flex flex-col"
             x-data="{ isUploading: false, progress: 0 }"
             x-on:livewire-upload-start="isUploading = true"
             x-on:livewire-upload-finish="isUploading = false"
             x-on:livewire-upload-error="isUploading = false"
             x-on:livewire-upload-progress="progress = $event.detail.progress"
        >

            <input wire:model.live="videoFile"
                   type="file"
                   id="videoFile"
                   name="videoFile"
                   class="dark:text-white"
            >
            <p class="pt-2 text-sm italic dark:text-white">
                * will start an Opencast workflow and your video will be transcoded directly to Opencast server
            </p>

            <!-- Progress Bar -->
            <div class="w-full shadow bg-grey" x-show="isUploading">
                <progress class="w-full text-center text-xs leading-none text-white bg-orange"
                          max="100"
                          x-bind:value="progress"></progress>
            </div>
        </div>

        <x-form.button link="$link=false"
                       type="submit"
                       text="Upload"
                       color="green"
                       additional-classes="w-full"
        />

        @error('videoFile')
        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </form>
</div>
