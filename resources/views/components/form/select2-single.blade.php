<div class="grid grid-cols-8">
    <div class="flex content-center items-center mb-6">
        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
               for="{{ $fieldName}}"
        >
            {{$label}}
        </label>
    </div>
    <div class="col-start-2 col-end-6">
        <select class="p-2 w-full {{ $selectClass}}
            focus:outline-none focus:bg-white focus:border-blue-500"
                name="{{ $fieldName }}"
                style="width: 100%"
        >

                @forelse($items as $item)
                    <option value="{{$item->id }}" {{ $isSelected($item->id) ? 'selected="selected"' : '' }}>
                        {{$item->name }}
                    </option>
                @empty
                    <option value="1" ></option>
                @endforelse
        </select>
    </div>

    @error($fieldName)
    <div class="col-span-8">
        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
    </div>
    @enderror
</div>
