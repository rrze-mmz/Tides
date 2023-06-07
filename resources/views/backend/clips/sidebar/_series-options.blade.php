<div class="mx-4 h-full w-full rounded border bg-white px-4 py-4">
    <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 py-4 pl-4 text-xl font-normal">

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
