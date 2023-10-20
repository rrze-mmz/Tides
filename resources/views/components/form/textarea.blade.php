<div class="grid grid-cols-8">
    <div class="content-center items-center">
        <label class="mr-6 block py-2 font-bold text-gray-700 text-md"
               for="{{ $fieldName }}"
        >
            {{ $label }}
        </label>
    </div>
    <div class="col-start-2 col-end-8">
        <x-trix name="{{$fieldName}}">{{$value}}</x-trix>
    </div>

    @error($fieldName)
    <div class="col-span-8">
        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
    </div>
    @enderror
</div>
