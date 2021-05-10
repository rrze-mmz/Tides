<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <header class="items-center pb-2 mb-2 font-semibold text-center border-b"> Belongs to:  {{ $clip->series()->first()->title  }} </header>
    <form action="{{ $clip->series()->first()->adminPath() }}"
          enctype="multipart/form-data"
          method="GET"
          class="flex flex-col"
    >
        <button type="submit"
                class="mt-2 py-2 px-8 text-white bg-green-500 rounded shadow hover:bg-green-600 focus:shadow-outline focus:outline-none"
        >View series</button>
    </form>
</div>
