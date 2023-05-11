<div {{ $attributes }}
     x-data="{ show: @entangle($attributes->wire('model')) }"
     x-show="show"
     @keydown.escape.window="show = false"
     x-on:click.away="show = false"
     style="display:none"
>
    <div class=" absolute bg-white shadow-md p-4 max-w-lg h-64  m-auto rounded-md  inset-0">
        <div class="flex flex-col h-full justify-between">
            <header class="flex justify-center">
                <h3 class="font-bold text-lg text-center">
                    {{ $title }}
                </h3>
            </header>
            <main class=" flex justify-center mb-4 ">
                {{ $body }}
            </main>
            <footer class="flex justify-center">
                {{ $footer  }}
            </footer>
        </div>
    </div>
</div>

