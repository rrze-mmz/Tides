@extends('layouts.myPortal')

@section('myPortalHeader')
    {{ __('myPortal.subscriptions.Your are subscribed to X Series', ['counter' => count($series)]) }}
@endsection

@section('myPortalContent')
    <div class="grid grid-cols-3 gap-4 mr-6">
        @forelse($series as $single)
            @include('backend.series._card',[
                    'series'=> $single,
                    'route' => 'admin'
                    ])
        @empty
            {{ __('homepage.series.no series subscriptions found') }}
        @endforelse
    </div>
@endsection
