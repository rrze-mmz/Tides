<?php

namespace App\Http\Livewire;

use App\Models\Clip;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CommentsSection extends Component
{
    use AuthorizesRequests;

    public Clip $clip;
    public $content;
    public $messageText;
    public $messageType;
    public $comments;

    protected $rules =[
        'content'   => 'required|min:3',
        'clip'  => 'required'
    ];

    public function fetchComments()
    {
        $this->comments = $this->clip->comments()->get();
    }
    public function mount(Clip $clip)
    {
        $this->clip = $clip;
    }

    public function postComment()
    {
        $this->authorize('create-comment');

        $this->validate();

//        sleep(1);

        Comment::create([
            'clip_id' => $this->clip->id,
            'content'   => $this->content,
            'owner_id'  => auth()->user()->id
        ]);

        $this->content = '';

        $this->clip->refresh();

        $this->messageText = 'Comment posted successfully';
        $this->messageType = 'success';
    }

    public function deleteComment(Comment $comment)
    {
        $this->authorize('delete-comment', $comment);

        $comment->delete();

        $this->clip->refresh();

        $this->messageText = 'Comment deleted successfully';
        $this->messageType = 'error';
    }

    public function render()
    {
        return view('livewire.comments-section');
    }
}
