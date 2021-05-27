
<div class="flex pt-8 pb-2 text-lg font-semibold border-b border-black">
    Clips
</div>
<x-list-clips :series="$series" dashboardAction="@can('edit-series', $series)"/>
