 <div class="grid grid-cols-8">
        <div class="content-center items-center">
            <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                   for="{{ $fieldName }}"
            >
                {{$label}}
            </label>
        </div>
        <div class="{{($fullCol)?'col-start-2 col-end-6':'w-full'}}">
            <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2
                                            border-gray-200 appearance-none focus:outline-none focus:bg-white
                                            focus:border-blue-500"
                   type="{{ $inputType }}"
                   name="{{ $fieldName }}"
                   id="{{ $fieldName }}"
                   value="{{ $value }}"
                   {{($required)?'required':''}}
                   {{($disabled)?'disabled':''}}
            >
        </div>
        @error($fieldName)
        <div class="col-start-2 col-end-6">
            <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
        </div>
        @enderror
    </div>
