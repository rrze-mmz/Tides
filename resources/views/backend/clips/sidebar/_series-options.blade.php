<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <h2 class="text-xl font-normal py-4 -ml-5 mb-3 border-l-4 border-blue-600 pl-4 ">

        Belongs to: {{ $clip->series->title  }}
    </h2>

    <x-form.button :link="$clip->series->adminPath()"
                   type="button"
                   text="View Series"
                   color="green"
                   additional-classes="w-full text-center my-2"
    />
    <form action="{{ route('series.clips.remove',$clip) }}"
          method="POST"
    >
        @csrf
        @method('DELETE')
        <x-form.button :link="route('series.clips.remove', $clip)"
                       type="submit"
                       text="Remove Series"
                       color="red"
                       additional-classes="w-full text-center my-2"
        />

    </form>
</div>
