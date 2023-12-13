@if($sortField !== $field)
    <x-heroicon-o-arrow-up class="ml-2 w-3 dark:text-white" />
    <x-heroicon-o-arrow-down class="ml-2 w-3 dark:text-white" />
@elseif($sortAsc)
    <x-heroicon-o-arrow-down class="ml-2 w-3 dark:text-white" />
@else
    <x-heroicon-o-arrow-up class="ml-2 w-3 dark:text-white" />
@endif
