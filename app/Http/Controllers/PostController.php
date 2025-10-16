<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->text = $request->text;

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('posts', 'public');
        }


        // 🔹 If user cropped image
        if ($request->filled('cropped_image')) {
            $imageData = $request->input('cropped_image');
            $image = str_replace('data:image/jpeg;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);

            $imageName = time() . '.jpg';
            Storage::disk('public')->put('posts/' . $imageName, base64_decode($image));

            $post->image = 'posts/' . $imageName;
        }
        // 🔹 Else, if normal file uploaded (fallback)
        elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    //Delete Post

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['success' => true]);
    }


    //Other methods

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'likes']); // Load relations
        return view('posts.show', compact('post'));
    }

    //guest index
    public function guestIndex()
    {
        $posts = Post::with(['user', 'comments', 'likes'])->latest()->paginate(5);
        return view('posts.guest-index', compact('posts'));
    }

    public function index()
    {
        $posts = Post::with([
            'user',
            'comments.user',
            'comments.currentUserLike', // For is_liked/isLikedByUser()
            'comments.post', // For canEditOrDelete()
            'likes'
        ])->latest()->simplePaginate(4);
        return view('posts.index', compact('posts'));
    }

    public function dashboard()
    {
        $posts = Post::where('user_id', Auth::id())
            ->with([
                'user',
                'comments.user',
                'comments.currentUserLike', // For is_liked/isLikedByUser()
                'comments.post', // For canEditOrDelete()
                'likes'
            ])->latest()->paginate(3);
        return view('dashboard', compact('posts'));
    }
    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'You cannot edit this post.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Check ownership
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'You cannot update this post.');
        }

        $request->validate([
            'text' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1048',
        ]);

        // Check if text has changed
        if ($post->text === $request->text) {
            return redirect()->route('posts.index')->with('info', 'You didn\'t change anything in the text.');
        }

        // Update text
        $post->text = $request->text;

        // Image handling (unchanged)
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }
            $post->image = $request->file('image')->store('posts', 'public');
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated!');
    }


}
