@if ($message = Session::get('flashMessage'))
    <div>
        <div
            x-data="{ show: false }"
            x-init="() => {
            setTimeout(() => show = true, 0);
            setTimeout(() => show = false, 2000);
          }"
            x-show="show"
            x-description="Notification panel, show/hide based on alert state."
            @click.away="show = false"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="flex rounded-md bg-green-200 p-2 mb-2">
            <div class="flex-shrink-0">
                <x-heroicon-o-check-circle class="w-5 h-5 text-green-400"/>
            </div>
            <div class="ml-3">
                <p class="text-sm leading-5 font-medium text-green-800">
                    {{ $message }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button @click="show = false"
                            class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100
                                 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150"
                            aria-label="Dismiss">
                        <x-heroicon-o-x-circle class="h-5 w-5"/>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
