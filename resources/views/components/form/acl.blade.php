 <div class="flex content-center items-center mb-6">
        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
               for="acls"
        >
            Accessible via
        </label>
    </div>
    <div class="col-span-7 w-4/5">
        <select class="p-2 w-full js-select2-tides-multiple
                                            focus:outline-none focus:bg-white focus:border-blue-500"
                name="acls[]"
                multiple="multiple"
                style="width: 100%"
        >
            @forelse($acls as $acl)
                <option value="{{ $acl->id }}"
                    @if($model?->acls->contains($acl->id)) {{'selected'}} @endif
                >{{ $acl->name }}</option>
            @empty
                <option value="1"></option>
            @endforelse
        </select>
    </div>
    @error('acls')
    <div class="col-span-8">
        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
    </div>
    @enderror
