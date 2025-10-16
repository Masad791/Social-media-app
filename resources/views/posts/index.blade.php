<x-app-layout>
    <x-slot name="header">
        {{ __('All Posts') }}
    </x-slot>
    <main class="container-index">
        <section class="feed">
            @if (session('success'))
                <div id="success-message" class="success-message">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif
            @forelse($posts as $post)
                <article class="post" id="post-{{ $post->id }}">
                    <div class="post-header">
                        <span class="username">{{ $post->user ? $post->user->name : 'Unknown User' }}</span>
                        @if (auth()->id() === $post->user_id)
                            <div class="menu-container">
                                <button class="menu-btn">⋮</button>
                                <div class="menu-dropdown hidden">
                                    <a href="{{ route('posts.edit', $post) }}" class="menu-item">Edit</a>
                                    <button type="button" class="menu-item delete-btn text-red-500"
                                        data-id="{{ $post->id }}"
                                        data-url="{{ route('posts.destroy', $post->id) }}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="post-content">
                        <a href="{{ route('posts.show', $post) }}">
                            @if ($post->text)
                                <p class="post-text">{{ $post->text }}</p>
                            @endif
                            @if ($post->image)
                                <img src="{{ Storage::url($post->image) }}" alt="Post Image" class="post-image">
                            @endif
                        </a>
                    </div>
                    <div class="post-actions">
                        <button type="button" class="like-btn" data-post-id="{{ $post->id }}"
                            data-csrf="{{ csrf_token() }}"
                            data-liked="{{ $post->likes->contains('user_id', Auth::id()) ? 'true' : 'false' }}">
                            <span
                                class="heart {{ $post->likes->contains('user_id', Auth::id()) ? 'liked' : '' }}">{{ $post->likes->contains('user_id', Auth::id()) ? '❤️' : '🤍' }}</span>
                            <span class="like-count">{{ $post->like_count }}</span>
                        </button>
                    </div>

                    <div class="comments-section">
                        <h3>Comments (<span class="comment-count">{{ $post->comments->count() }}</span>)</h3>
                        <div class="comments-list" id="comments-list-{{ $post->id }}">
                            @foreach ($post->comments()->latest()->take(1)->get() as $comment)
                                <div class="comment" id="comment-{{ $comment->id }}">
                                    <span
                                        class="username">{{ $comment->user ? $comment->user->name : 'Unknown User' }}</span>:
                                    <span class="comment-text">{{ $comment->comment }}</span>
                                    <div class="comment-meta">
                                        <small class="timestamp">{{ $comment->created_at->diffForHumans(["parts"=>2, "join"=>",", "short"=> true ]) }}</small>
                                        @auth
                                            {{-- @if (Auth::id() === $comment->user_id)
                                                <button type="button"
                                                    class="edit-comment-btn text-blue-500 hover:text-blue-700 ml-2"
                                                    data-comment-id="{{ $comment->id }}"
                                                    data-csrf="{{ csrf_token() }}">Edit</button>
                                            @endif --}}
                                            @if (auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                                <button type="button"
                                                    class="delete-comment-btn text-red-500 hover:text-red-700 ml-2"
                                                    data-comment-id="{{ $comment->id }}"
                                                    data-post-id="{{ $post->id }}"
                                                    data-csrf="{{ csrf_token() }}">Delete</button>
                                            @endif
                                            <button type="button" class="like-comment-btn"
                                                data-comment-id="{{ $comment->id }}" data-csrf="{{ csrf_token() }}"
                                                data-liked="{{ $comment->isLikedByUser() ? 'true' : 'false' }}">
                                                <span
                                                    class="heart {{ $comment->isLikedByUser() ? 'liked' : '' }}">{{ $comment->isLikedByUser() ? '❤️' : '🤍' }}</span>
                                                <span class="like-count">{{ $comment->likeCount() }} likes</span>
                                            </button>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($post->comments->count() > 1)
                            <div class="comments-toggle" id="comments-toggle-{{ $post->id }}">
                                <button type="button" class="see-more-btn" data-post-id="{{ $post->id }}"
                                    data-csrf="{{ csrf_token() }}" data-page="1">
                                    See more comments
                                </button>
                                <button class="see-less-btn hidden" data-post-id="{{ $post->id }}">See
                                    less</button>
                            </div>
                        @endif

                        <form id="comment-form-{{ $post->id }}" class="comment-form"
                            data-post-id="{{ $post->id }}" data-csrf="{{ csrf_token() }}">
                            <input type="text" name="comment" placeholder="Add a comment..." class="comment-input"
                                required>
                            @error('comment')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <button type="submit" class="comment-btn">Comment</button>
                        </form>
                    </div>
                </article>
            @empty
                <p>No posts yet. <a href="{{ route('posts.create') }}">Create one!</a></p>
            @endforelse
            <div class="pagination">
                {{ $posts->links() }}
            </div>
        </section>
    </main>
    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg">
            <p>Are you sure you want to delete this post?</p>
            <div class="mt-4 flex justify-end gap-2">
                <button id="cancelDelete" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
                <button id="confirmDelete" class="px-3 py-1 bg-red-500 text-white rounded">Delete</button>
            </div>
        </div>
    </div>

    <!-- Undo Toast -->
    <div id="undoToast"
        class="hidden fixed bottom-5 right-5 bg-gray-900 text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-3 animate-fade-in">
        <span>Post deleted.</span>
        <button id="undoBtn"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-3 py-1 rounded-md transition-colors duration-200">
            Undo
        </button>
    </div>

</x-app-layout>
