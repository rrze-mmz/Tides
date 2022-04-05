<div class="flex items-center pt-3 space-x-2">
    <x-form.button :link="route('frontend.series.show',$series)" type="submit"
                   text="Go to public page"/>

    <x-form.button :link="route('series.clips.create',$series)" type="submit" text="Add new clip"/>

    @can('update-series', $series)
        <form action="{{$series->adminPath()}}"
              method="POST">
            @csrf
            @method('DELETE')
            <x-form.button :link="$link=false" type="delete" text="Delete Series" color="red"/>
        </form>
    @endcan
</div>
