<div class="grid grid-cols-12 gap-4 content-center items-center ">
    <div class=" col-span-2 ">
        <label for="{{ $fieldName }}"
               class="block py-2 mr-6 font-bold text-gray-700 text-md"
        >
            {{ $label }}
        </label>
    </div>
    <div class="">
        <div class="py-2  w-full bg-none" x-data="{ checked: {{ ($value ? 'true' : 'false') }} }">
            <div class="relative  w-12 h-6 transition duration-200 ease-in select-none bg-none">
                <input type="checkbox"
                       id="{{ $fieldName }}"
                       name="{{ $fieldName }}"
                       class=" rounded-full  w-full h-full active:outline-none focus:outline-none bg-none"
                       x-model="checked"
                >
                <label for="{{ $fieldName }}"
                       class=" absolute left-0  border-2 mb-2 w-6 h-6
                               rounded-full transition transform  bg-blue-500
                            duration-100 ease-linear cursor-pointer rounded-full  bg-blue-500"
                       :class="checked ? 'translate-x-full bg-blue bg-none ' : 'translate-x-0 border-gray-400'"
                ></label>
            </div>
        </div>
    </div>
    @error($fieldName)
    <div class="col-span-8">
        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
    </div>
    @enderror
</div>
