@if($type =='back')
    <a href="{{$link}}"
       type="button"
       class="items-center py-1 px-4  border border-transparent text-base leading-6 font-medium rounded-md text-white
                focus:shadow-outline-indigo bg-{{ $color!==''?$color:'blue' }}-600
                hover:bg-{{ $color!==''?$color:'blue' }}-700hover:shadow-lg {{ $additionalClasses }}"
    >
        {{$text}}
    </a>
@else
    <a href="{{$link}}">
        <button type="{{ $type }}"
                class="items-center px-4 py-1 border border-transparent text-base leading-6 font-medium rounded-md
                        text-white bg-{{ $color!==''?$color:'blue' }}-600  focus:shadow-outline-indigo
                        hover:bg-{{ $color!==''?$color:'blue' }}-700 hover:shadow-lg {{ $additionalClasses }}"
        >
            {{ $text }}
        </button>
    </a>
@endif

