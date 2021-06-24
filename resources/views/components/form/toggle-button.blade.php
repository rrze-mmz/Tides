<div class="grid grid-cols-8">
    <div class="content-center items-center">
        <label for="{{ $fieldName }}"
               class="block py-2 mr-6 font-bold text-gray-700 text-md"
        >
            {{ $label }}
        </label>
    </div>
    <div class="col-start-2 col-end-6">
        <div class="py-2  w-full" x-data="{ checked: {{ ($value ? 'true' : 'false') }} }">
            <div class="relative rounded-full w-12 h-6 transition duration-200 ease-linear"
                 :class="checked ? 'bg-green-400' : 'bg-gray-400'">
                <label for="{{ $fieldName }}"
                       class="absolute left-0 bg-white border-2 mb-2 w-6 h-6 rounded-full transition transform
                            duration-100 ease-linear cursor-pointer"
                       :class="checked ? 'translate-x-full border-green-400' : 'translate-x-0 border-gray-400'"
                ></label>
                <input type="checkbox" id="{{ $fieldName }}" name="{{ $fieldName }}"
                       class="appearance-none w-full h-full active:outline-none focus:outline-none"
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
