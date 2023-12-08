<div class="grid grid-cols-12 content-center items-center gap-4">
    <div class=" {{ $labelClass }} ">
        <label for="{{ $fieldName }}"
               class="mr-6 block py-2 font-bold text-gray-700 text-md dark:text-white"
        >
            {{ $label }}
        </label>
    </div>
    <div class="">
        <div class="w-full bg-none" x-data="{ checked: {{ ($value ? 'true' : 'false') }} }">
            <div class="relative h-6 w-12 select-none bg-none transition duration-200 ease-in dark:text-white">
                <label for="{{ $fieldName }}"
                       class="absolute left-0  border-2 mb-2 w-6 h-6
                               rounded-full transition transform  bg-blue-500
                            duration-100 ease-linear cursor-pointer"
                       :class="checked ? 'translate-x-full bg-blue bg-none ' : 'translate-x-0 border-gray-400'"
                ></label>
                <input type="checkbox"
                       id="{{ $fieldName }}"
                       name="{{ $fieldName }}"
                       class="mb-1 h-full w-full rounded-full bg-none focus:outline-none active:outline-none"
                       x-model="checked"
                >
            </div>
        </div>
    </div>
    @error($fieldName)
    <div class="col-span-8">
        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
    </div>
    @enderror
</div>
