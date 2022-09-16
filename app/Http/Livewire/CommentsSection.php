<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\NewComment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class CommentsSection extends Component
{
    use AuthorizesRequests;

    public $model;

    public $content;

    public $messageText = '';

    public $messageType = '';

    public $type;

    public $comments;

    protected array $rules = [
        'content' => 'required|min:3',
        'model' => 'required',
        'type' => 'required|string',
    ];

    /**
     * Get all comments for a clip
     */
    public function fetchComments(): void
    {
        $this->comments = $this->model->comments()->where('type', $this->type)->get();
    }

    public function mount()
    {
        $this->comments = $this->model->comments()->where('type', $this->type)->get();
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

        $comment = $this->model->comments()->save(Comment::create([
            'content' => $this->content,
            'owner_id' => auth()->user()->id,
            'type' => $this->type,
        ]));

        $this->content = '';

        $this->model->refresh();

        //don't notify user for self posted comments
        if (auth()->user()->isNot($this->model->owner)) {
            if (is_null($this->model->owner)) {
                Notification::sendNow(User::admins()->get(), new NewComment($comment));
            } else {
                Notification::sendNow($this->model->owner, new NewComment($comment));
            }
        }

        $this->messageText = 'Comment posted successfully';
        $this->messageType = 'success';
        $this->fetchComments();
        $this->emit('updated');
    }

    /**
     * Delete a single comment
     *
     * @param  Comment  $comment
     *
     * @throws AuthorizationException
     */
    public function deleteComment(Comment $comment): void
    {
        $this->authorize('delete-comment', $comment);

        $comment->delete();

        $this->model->refresh();
        $this->fetchComments();

        $this->messageText = 'Comment deleted successfully';
        $this->messageType = 'error';
        $this->emit('updated');
    }

    /**
     * Render Livewire component
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.comments-section');
    }
}
