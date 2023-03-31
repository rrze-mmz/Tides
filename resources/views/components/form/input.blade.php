<div class="grid grid-cols-8 items-center">
    <div class="content-center">
        <label class="block py-4 mr-2 font-bold text-gray-700 text-md"
               for="{{ $fieldName }}"
        >
            {{$label}}
        </label>
    </div>
    <div class="{{($fullCol)?'col-start-2 col-end-8':'w-full'}}">
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
    @if ($disabled)
        <div class="col-start-2 col-end-6">
            <p class="mt-2 w-full text-xs">
            <div class="flex text-green-500">
                <x-heroicon-o-exclamation-circle class="w-6"/>
                <span class="pl-2">
                        You cannot change this field
                    </span>
            </div>
            </p>
        </div>
    @endif
    @error($fieldName)
    <div class="col-start-2 col-end-6">
        <p class="mt-2 w-full text-xs text-red500">{{ $message }}</p>
    </div>
    @enderror
</div>
