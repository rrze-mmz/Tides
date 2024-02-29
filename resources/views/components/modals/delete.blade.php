@php use Illuminate\Support\Str; @endphp
@props(['route','btn_text'=> Str::ucfirst(__('common.actions.delete'))])
<div x-data="{ open: false }">
    <!-- Modal toggle -->
    <x-button @click="open = !open" type="button" {{ $attributes->merge(['class'=> 'bg-red-600 hover:bg-red-700']) }}>
        {{ $btn_text }}
    </x-button>
    {{--    <button @click="open = !open"--}}
    {{--            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300--}}
    {{--        font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700--}}
    {{--        dark:focus:ring-blue-800"--}}
    {{--            type="button"--}}
    {{--    >--}}
    {{--        Toggle modal--}}
    {{--    </button>--}}

    <div x-show="open" @click.away="open = false" @keydown.escape.window="open = false"
         class="absolute inset-0 m-auto h-64 max-w-lg rounded-md dark:bg-slate-800">
        <!-- Modal content -->
        <div class="bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-se text-gray-900 dark:text-white">
                    {{ $title }}
                </h3>
                <button @click="open = false" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg
                        text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600
                        dark:hover:text-white"
                        data-modal-hide="static-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="{{$route}}" method="POST">
                @method('DELETE')
                @csrf
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <p class="text-base leading-relaxed text-gray-500 dark:text-white">
                        {{ $body }}
                    </p>
                </div>
                <!-- Modal footer -->
                <div
                    class="flex justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none
                            focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                            dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-blue-800">
                        {{  $btn_text }}
                    </button>
                    <button @click="open = false" data-modal-hide="static-modal" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-white
                            rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10
                            focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800
                            dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        {{__('common.forms.decline')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
