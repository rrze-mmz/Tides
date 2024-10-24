@extends('layouts.myPortal')

@section('myPortalHeader')
    <div class="dark:text-white">
        {{ __('myPortal.subscriptions.Your are subscribed to X Series', ['counter' => count($series)]) }}
    </div>
@endsection

@section('myPortalContent')
    <div class="mr-6 grid grid-cols-3 gap-4">
        @forelse($series as $single)
            @include('backend.series._card',[
                    'series'=> $single,
                    'route' => 'admin'
                    ])
        @empty
            <div class="dark:text-white">
                {{ __('homepage.series.no series subscriptions found') }}
            </div>

        @endforelse
    </div>
@endsection
