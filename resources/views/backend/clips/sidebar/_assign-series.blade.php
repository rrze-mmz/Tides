<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border my-4">
    <header class="items-center pb-2 mb-4 font-semibold text-center border-b">
        Assign series to this clip
    </header>
    <x-form.button :link="route('series.clips.listSeries',$clip)"
                   type="back"
                   text="Search for series"
                   color="green"
                   additional-classes="w-full"
    />
    </form>
</div>
