<div class="grid grid-cols-8">
    <div class="mb-6 flex content-center items-center">
        <label class="mr-6 block py-2 font-bold text-gray-700 text-md"
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
                <option value="{{$item->id }}" {{ $isSelected($item->id) ? 'selected' : '' }}>
                    {{$item->name }}
                </option>
            @empty
                <option value="1"></option>
            @endforelse
        </select>
    </div>

    @error($fieldName)
    <div class="col-span-8">
        <p class="mt-2 w-full text-xs text-red-500">{{ $mssage }}</p>
    </div>
    @enderror
</div>
