@extends('layouts.myPortal')

@section('myPortalHeader')
    {{ __('myPortal.comments.You have X comments', ['counter' => $comments->count() ]) }}
@endsection

@section('myPortalContent')
    @foreach ($comments->sortDesc() as $comment)
        <div class="border-b-2 border-black font-extrabold">
            For {{ str($comment->commentable_type)->ucfirst() }} : {{ $comment->commentable->title }}
        </div>
        <div class="my-10 flex justify-between">
            <img class="h-10 w-10 flex-none rounded-full"
                 src="{{ URL::asset('/images/none.jpg') }}"
                 alt="avatar">
            <div class="ml-4 flex-grow">
                <div class="flex items-center">
                    <div class="font-semibold"> You</div>
                    <div class="ml-2 text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                <div class="flex flex-col">
                    <div class="mt-2 w-full text-gray-700">{{ $comment->content }}</div>
                    <div class="flex">
                        <a href="{{ route('frontend.'.str($comment->commentable_type)->plural().'.show',$comment->commentable) }}"
                           class="pt-2 text-red-800 underline hover:text-red-700">
                            <span>Go to {{ $comment->commentable_type }} </span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
@endsection
