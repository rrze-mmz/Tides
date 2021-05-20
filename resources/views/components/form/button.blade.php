@if($link)
    <a href="{{$link}}">
        <button type="{{ $type }}"
                class="py-2 px-8  focus:outline-none text-white rounded-md
            bg-{{ $type=='submit'?'blue':'red' }}-700 hover:bg-{{ $type=='submit'?'blue':'red' }}-600
            hover:shadow-lg"
        >
            {{ $text }}
        </button>
    </a>
@else
    <button type="{{ $type }}"
            class="py-2 px-8  focus:outline-none text-white rounded-md
        bg-{{ $type=='submit'?'blue':'red' }}-700 hover:bg-{{ $type=='submit'?'blue':'red' }}-600
        hover:shadow-lg"
    >
        {{ $text }}
    </button>
@endif
