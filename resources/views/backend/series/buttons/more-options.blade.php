<div class="flex items-center pt-3 space-x-2">
    <x-form.button :link="route('series.clips.changeEpisode',$series)" type="submit"
                   text="Reorder clips"/>
    <x-form.button :link="route('series.chapters.index',$series)" type="submit"
                   text="Manage chapters"/>
</div>
