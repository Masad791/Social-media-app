<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CommentList extends Component
{
    use WithPagination;

    public $postId;
    public $content = '';

    protected $paginationTheme = 'tailwind'; // Use Tailwind for pagination styles

    public function mount($postId)
    {
        $this->postId = $postId;
    }

    public function addComment()
    {
        $this->validate([
            'content' => ['required', 'string', 'max:500'],
        ]);

        $post = Post::findOrFail($this->postId);
        $post->comments()->create([
            'content' => $this->content,
            'user_id' => Auth::id(),
        ]);

        $this->content = ''; // Clear input
        $this->resetPage(); // Reset pagination to show new comment at top
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        if (Auth::id() === $comment->user_id) {
            $comment->delete();
        }
    }

    public function render()
    {
        $post = Post::findOrFail($this->postId);
        $comments = $post->comments()->with('user')->latest()->paginate(10);

        return view('livewire.comment-list', [
            'comments' => $comments,
            'post' => $post,
        ]);
    }
}