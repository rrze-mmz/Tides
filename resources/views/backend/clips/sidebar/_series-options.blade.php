<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <header class="items-center pb-2 mb-2 font-semibold text-center border-b"> Belongs to:  {{ $clip->series()->first()->title  }} </header>
    <form action="{{ $clip->series()->first()->adminPath() }}"
          enctype="multipart/form-data"
          method="GET"
          class="flex flex-col"
    >
        <button type="submit"
                class=" mt-3 ml-2 focus:outline-none text-white text-sm py-1.5 px-5 rounded-md bg-green-500 hover:bg-green-600 hover:shadow-lg"
        >Go back to series</button>
    </form>
</div>
