<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <h2 class="text-xl font-normal py-4 -ml-5 mb-3 border-l-4 border-blue-600 pl-4 ">
        Series Administrator
    </h2>
    <div class="flex">
        <div class="text-lg italic">
            {{$series->owner?->getFullNameAttribute().'-'.$series->owner?->username}}
        </div>
    </div>

    @if($series->members()->count() > 0)
        <h4 class="pt-6 border-b-2 pb-2">
            Members
        </h4>
        <div class="pt-4">
            <ul class="list-disc">
                @foreach($series->members as $member)
                    <li class="p-2 mx-4">
                        {{ $member->getFullNameAttribute() }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
