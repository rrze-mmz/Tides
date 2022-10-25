@extends('layouts.myPortal')

@section('myPortalHeader')
    Your have {{ $comments->count()  }} Comments
@endsection

@section('myPortalContent')
    @foreach ($comments->sortDesc() as $comment)
        <div class="font-extrabold border-b-2 border-black">
            For {{ str($comment->commentable_type)->ucfirst() }} : {{ $comment->commentable->title }}
        </div>
        <div class="flex justify-between my-10">
            <img class="flex-none h-10 w-10 rounded-full"
                 src="{{ URL::asset('/images/none.jpg') }}"
                 alt="avatar">
            <div class="ml-4 flex-grow">
                <div class="flex items-center">
                    <div class="font-semibold"> You</div>
                    <div class="text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                <div class="flex flex-col">
                    <div class="text-gray-700 mt-2 w-full ">{{ $comment->content }}</div>
                    <div class="flex">
                        <a href="{{ route('frontend.'.str($comment->commentable_type)->plural().'.show',$comment->commentable) }}"
                           class="pt-2 underline text-red-800 hover:text-red-700">
                            <span>Go to {{ $comment->commentable_type }} </span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
@endsection
