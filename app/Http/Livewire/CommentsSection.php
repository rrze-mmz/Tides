<?php

namespace App\Http\Livewire;

use App\Models\Clip;
use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
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

    protected array $rules =[
        'content'   => 'required|min:3',
        'clip'  => 'required'
    ];

    /**
     * Get all comments for a clip
     */
    public function fetchComments(): void
    {
        $this->comments = $this->clip->comments()->get();
    }

    /**
     * Mount Livewire component
     * @param Clip $clip
     */
    public function mount(Clip $clip): void
    {
        $this->clip = $clip;
        $this->messageType ='';
        $this->messageText = '';
    }

    /**
     * Post a comment for a clip
     *
     * @throws AuthorizationException
     */
    public function postComment(): void
    {
        $this->authorize('create-comment');

        $this->validate();

        Comment::create([
            'clip_id' => $this->clip->id,
            'content'   => $this->content,
            'owner_id'  => auth()->user()->id
        ]);

        $this->content = '';

        $this->clip->refresh();

        $this->messageText = 'Comment posted successfully';
        $this->messageType = 'success';
        $this->emit('updated');
    }

    /**
     * Delete a single comment
     *
     * @param Comment $comment
     * @throws AuthorizationException
     */
    public function deleteComment(Comment $comment): void
    {
        $this->authorize('delete-comment', $comment);

        $comment->delete();

        $this->clip->refresh();

        $this->messageText = 'Comment deleted successfully';
        $this->messageType = 'error';
        $this->emit('updated');
    }

    /**
     * Render Livewire component
     * @return View
     */
    public function render(): View
    {
        return view('livewire.comments-section');
    }
}
