<div class="px-4 py-4 mx-4 my-4 w-full h-full bg-white rounded border">
    <header class="items-center pb-2 mb-4 font-semibold text-center border-b">
        Assign series to this clip
    </header>
    <a href="{{route('series.clips.listSeries',$clip)}}">
        <button type="button" class="items-center px-4 py-1 border border-transparent text-base leading-6
                                font-medium rounded-md text-white
                        bg-green-600  focus:shadow-outline-indigo hover:bg-green-700
                        hover:shadow-lg w-full">
            View Series
        </button>
    </a>
    </form>
</div>
