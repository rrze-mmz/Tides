<div class="flex flex-col">
        <div class="flex">
            <h2 class="text-2xl w-full">
                {{$series->title}}
            </h2>
            @can('edit-series',$series)
                <a href="{{$series->adminPath()}}"
                        class="ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg"
                >Edit</a>
            @endcan
        </div>

        <p class="pt-2 text-xl italic">
            {{ $series->description }}
        </p>
</div>
