<div class="flex pt-8 pb-2 text-lg font-semibold border-b border-black mb-4">
    Clips
</div>
<x-list-clips :series="$series" dashboardAction="@can('menu-dashboard-admin')"/>
