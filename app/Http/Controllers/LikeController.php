<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
     public function ajaxToggleLike(Request $request, Post $post)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in'], 401);
        }

        $user = Auth::user();
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $post->decrement('like_count');
            $action = 'unliked';
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $post->increment('like_count');
            $action = 'liked';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'like_count' => $post->like_count,
        ]);
    }
}
