<div class="grid grid-cols-8">
    <div class="content-center items-center">
        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
               for="{{ $fieldName }}"
        >
            {{ $label }}
        </label>
    </div>
    <div class="col-start-2 col-end-8">
        <x-trix name="description">{{$value}}</x-trix>
    </div>

    @error($fieldName)
    <div class="col-span-8">
        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
    </div>
    @enderror
</div>
