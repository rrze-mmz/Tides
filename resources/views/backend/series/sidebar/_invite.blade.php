<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <h2 class="text-xl font-normal py-4 -ml-5 mb-3 border-l-4 border-blue-600 pl-4 ">
        {{ __('series.backend.Add a series member')  }}
    </h2>

    <form
        method="POST"
        class="px-2"
        action="{{route('series.membership.addUser',$series)}}"
    >
        @csrf

        <div class="w-full pb-6">
            <select class="p-2 w-full select2-tides-users focus:outline-none focus:bg-white focus:border-blue-500"
                    name="userID"
                    style="width: 100%"
            >
            </select>
        </div>
        <x-button class="bg-blue-600 hover:bg-blue-700">
            Add
        </x-button>
    </form>
</div>
