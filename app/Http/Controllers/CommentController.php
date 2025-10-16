<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class CommentController extends Controller
{
        public function ajaxAddComment(Request $request, Post $post)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in'], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_name' => Auth::user()->name,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(["parts"=>2, "join"=>",", "short"=> true ]),
            ],
            'comment_count' => $post->comments()->count(),
        ]);
    }

    // ----- see more comments 

     public function ajaxLoadComments(Post $post)
{
    $perPage = 7;
    // Add eager loading for currentUserLike, user, and post
    $comments = $post->comments()->with(['currentUserLike', 'user', 'post'])->latest()->paginate($perPage);
    $html = '';
    foreach ($comments as $comment) {
        $html .= '<div class="comment" id="comment-' . $comment->id . '">';
        $html .= '<span class="username">' . ($comment->user ? htmlspecialchars($comment->user->name) : 'Unknown User') . '</span>: ';
        $html .= '<span class="comment-text">' . htmlspecialchars($comment->comment) . '</span>';
        $html .= '<div class="comment-meta">';
        $html .= '<small class="timestamp">' . $comment->created_at->diffForHumans(["parts" => 2, "join" => ",", "short" => true]) . '</small>';
        if (Auth::check()) {
            if ($comment->canEditOrDelete()) { // Uses preloaded post
                $html .= '<button type="button" class="delete-comment-btn text-red-500 hover:text-red-700 ml-2" data-comment-id="' . $comment->id . '" data-post-id="' . $post->id . '" data-csrf="' . csrf_token() . '">Delete</button>';
            }
            $html .= '<button type="button" class="like-comment-btn" data-comment-id="' . $comment->id . '" data-csrf="' . csrf_token() . '" data-liked="' . ($comment->is_liked ? 'true' : 'false') . '">';
            $html .= '<span class="heart ' . ($comment->is_liked ? 'liked' : '') . '">' . ($comment->is_liked ? '❤️' : '🤍') . '</span>';
            $html .= '<span class="like-count">' . $comment->like_count . ' likes</span>';
            $html .= '</button>';
        }
        $html .= '</div>';
        $html .= '</div>';
    }
    Log::info('HTML generated:', ['html_length' => strlen($html)]);
    return response()->json([
        'success' => true,
        'html' => $html,
        'comment_count' => $comments->count(),
    ]);
}


public function guestAjaxLoadComments(Post $post)
{
    $perPage = 10;
    $comments = $post->comments()
        ->with('user')
        ->withCount('likes')
        ->latest()
        ->paginate($perPage);

    $html = view('partials.guest-comment', ['comments' => $comments])->render();

    return response()->json([
        'success' => true,
        'html' => $html,
        'comment_count' => $comments->count(),
    ]);
}


    public function deleteComment(Comment $comment)
    {
        if (!Auth::check() || (Auth::id() !== $comment->user_id && Auth::id() !== $comment->post->user_id)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $postId = $comment->post_id;
        $comment->delete();

        return response()->json([
            'success' => true,
            'post_id' => $postId,
            'comment_count' => Post::find($postId)->comments()->count(),
        ]);
    }

   public function like(Comment $comment)
{
    if (!Auth::check()) {
        Log::error('Unauthorized like attempt', ['comment_id' => $comment->id]);
        return response()->json(['error' => 'You must be logged in'], 401);
    }

    // Use the scoped relationship (one query, only for current user)
    $like = $comment->currentUserLike()->first();
    $liked = !$like;

    if ($like) {
        $like->delete();
        $comment->decrement('like_count');
    } else {
        $comment->likes()->create(['user_id' => Auth::id()]);
        $comment->increment('like_count');
    }

    // Refresh to ensure like_count is accurate
    $comment->refresh();

    $response = [
        'success' => true,
        'liked' => $liked,
        'like_count' => $comment->like_count,
        'is_liked' => $comment->is_liked, // Uses cached accessor
    ];
    Log::info('Comment like processed', ['comment_id' => $comment->id, 'response' => $response]);
    return response()->json($response, 200);
}

    public function edit(Comment $comment)
    {
        if (!Auth::check() || Auth::id() !== $comment->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'success' => true,
            'comment' => $comment->comment,
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        if (!Auth::check() || Auth::id() !== $comment->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'success' => true,
            'comment' => $comment->comment,
        ]);
    }
}
