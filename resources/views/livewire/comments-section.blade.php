<div class="mb-2 space-y-10" id="comments-section">
        <x-message
            :messageText="$messageText"
            :messageType="$messageType"/>

    <form wire:submit.prevent="postComment" action="#" method="POST" class="w-1/2 my-12">
        @csrf
        <div class="flex">
            <img class="h-10 w-10 rounded-full" src="https://www.gravatar.com/avatar/?d=mp&f=y" alt="avatar">
            <div class="ml-4 flex-1">
                <textarea wire:model.defer="content" name="content" id="content" rows="4" placeholder="Type your comment here..."
                           class="border rounded-md shadow w-full px-4 py-2"></textarea>

                @error('content')
                <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6
                                font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none
                                focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition
                                 ease-in-out duration-150 mt-2 disabled:opacity-50">
                    <svg wire:loading wire:target="postComment" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>Post Comment</span>
                </button>

            </div>
        </div>
    </form>

        <div wire:poll.30s="fetchComments">
            @foreach ($clip->comments->sortDesc() as $comment)
                <div class="flex justify-between my-10">
                    <img class="flex-none h-10 w-10 rounded-full" src="https://www.gravatar.com/avatar/?d=mp&f=y" alt="avatar">
                    <div class="ml-4 flex-grow">
                        <div class="flex items-center">
                            <div class="font-semibold">{{ $comment->owner->name }}</div>
                            <div class="text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="flex flex-col">
                            <div class="text-gray-700 mt-2 w-full ">{{ $comment->content }}</div>
                            @can('delete-comment', $comment)
                                <div class="flex">
                                    <button wire:click = "deleteComment({{ $comment }})" type="submit"
                                            class="mt-3  items-center px-1 py-1 border border-transparent text-base leading-2
                                            text-xs rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none
                                            focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition
                                             ease-in-out duration-150 disabled:opacity-50">
                                        <span>Delete Comment</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

</div>
