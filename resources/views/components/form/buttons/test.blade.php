@props(['type' => 'submit', 'link', 'message','color'])

<a href="{{$link}}">
    <button type="{{ $type }}"
            class="items-center px-4 py-1 border border-transparent text-base leading-6
                                font-medium rounded-md text-white
                        bg-blue-600  focus:shadow-outline-indigo hover:bg-blue-700
                        hover:shadow-lg"
    >
        {{ $message }}
    </button>
</a>
