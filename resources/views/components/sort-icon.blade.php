@if($sortField !== $field)
    <x-heroicon-o-arrow-up class="ml-2 w-3"/>
    <x-heroicon-o-arrow-down class="ml-2 w-3"/>
@elseif($sortAsc)
    <x-heroicon-o-arrow-down class="ml-2 w-3"/>
@else
    <x-heroicon-o-arrow-up class="ml-2 w-3"/>
@endif
