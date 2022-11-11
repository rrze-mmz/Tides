<div {{ $attributes }}>
    <div class="m-2 p-2 border-black border-solid rounded-lg border-2 bg-white">
        <div class="flex flex-col justify-between place-content-around">
            <div>
                <h3 class="pb-6 font-semibold font-light">{{ $title }}
                </h3>
            </div>
            <div>
                <a href="{{ $route }}">
                    <x-button type="button" class="bg-blue-600 hover:bg-blue-700">
                        {{ $text  }}
                    </x-button>
                </a>
            </div>
        </div>
    </div>
</div>
