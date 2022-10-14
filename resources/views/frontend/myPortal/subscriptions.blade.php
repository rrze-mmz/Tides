@extends('layouts.myPortal')

@section('myPortalHeader')
    Your are subscribed to {{ count($series) }} Series
@endsection

@section('myPortalContent')
    <div class="grid grid-cols-3 gap-4 mr-6">
        @forelse($series as $single)
            @include('backend.series._card',[
                    'series'=> $single,
                    'route' => 'admin'
                    ])
        @empty
            {{ __('homepage.series.no series  subscripions found' )}}
        @endforelse
    </div>
@endsection
