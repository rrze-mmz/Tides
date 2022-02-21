<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <header class="items-center pb-2 mb-2 font-semibold text-center border-b">
        Belongs to: {{ $clip->series->title  }} </header>

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
