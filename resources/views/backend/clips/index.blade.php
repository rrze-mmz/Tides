@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black pb-2 font-semibold font-2xl">
        Clips index
    </div>
    <livewire:clips-data-table />
@endsection

