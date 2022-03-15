<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <header class="items-center pb-2 mb-2 font-semibold text-center border-b">
        Series owner
    </header>
    <div class="flex">
        <div class="mx-auto">
            {{$series->owner?->getFullNameAttribute()}}
        </div>
    </div>
</div>
