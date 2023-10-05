<div {{ $attributes }}
     x-data="{ show: @entangle($attributes->wire('model')).live }"
     x-show="show"
     @keydown.escape.window="show = false"
     x-on:click.away="show = false"
     style="display:none"
>
    <div class="absolute inset-0 m-auto h-64 max-w-lg rounded-md bg-white p-4 shadow-md">
        <div class="flex h-full flex-col justify-between">
            <header class="flex justify-center">
                <h3 class="text-center text-lg font-bold">
                    {{ $title }}
                </h3>
            </header>
            <main class="mb-4 flex justify-center">
                {{ $body }}
            </main>
            <footer class="flex justify-center">
                {{ $footer  }}
            </footer>
        </div>
    </div>
</div>

