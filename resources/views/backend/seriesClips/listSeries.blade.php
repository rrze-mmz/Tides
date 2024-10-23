@extends('layouts.backend')

@section('content')
    <div class="flex border-b border-black text-2xl flex-col dark:text-white dark:border-white font-normal pb-2">
        <div class="font-semibold ">
            {{ __('series.backend.Select a series for clip', ['clip_title' => $clip->title]) }}
        </div>
    </div>
    <livewire:index-pages-datatable :action-button="'assignClip'" :action-obj="$clip" />
@endsection
