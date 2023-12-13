<div class="grid grid-cols-8 items-center">
    <div class="content-center">
        <div class="flex flex-col">
            <label class="mr-2 block flex py-2 font-bold  dark:text-white text-gray-700 text-md"
                   for="{{ $fieldName }}"
            >
            <span>
                {{$label}}
            </span>
            </label>
            @if ($disabled)
                <div class="flex text-sm text-green-500">
                    (You cannot change this field)
                </div>
            @endif
        </div>
    </div>
    <div class="{{($fullCol)?'col-start-2 col-end-8':'w-full'}}">
        <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2
                                            border-gray-200 appearance-none focus:outline-none focus:bg-white
                                            focus:border-blue-500 dark:focus:border-orange-600 "
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
