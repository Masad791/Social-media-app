@extends('layouts.layout')

@section('content')
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
                    </div>
                    <div class="post-content">
                            @if ($post->text)
                                <p class="post-text">{{ $post->text }}</p>
                            @endif
                            @if ($post->image)
                                <img src="{{ Storage::url($post->image) }}" alt="Post Image" class="post-image">
                            @endif
                        </a>
                    </div>
                    <div class="post-actions">
                        <span class="heart">❤️</span>
                        <span class="like-count">{{ $post->like_count }}</span>
                    </div>

                    <div class="comments-section">
                        <h3><span class="heart">💬</span> Comments (<span
                                class="comment-count">{{ $post->comments->count() }}</span>)</h3>
                        <div class="comments-list" id="comments-list-{{ $post->id }}">
                            @foreach ($post->comments()->withCount('likes')->latest()->take(1)->get() as $comment)
                                <div class="comment" id="comment-{{ $comment->id }}">
                                    <span class="username">{{ $comment->user->name ?? 'Unknown User' }}</span>:
                                    <span class="comment-text">{{ $comment->comment }}</span>
                                    <div class="comment-meta">
                                        <small
                                            class="timestamp">{{ $comment->created_at->diffForHumans(['parts' => 2, 'join' => ',', 'short' => true]) }}</small>
                                        <span class="heart">{{ $comment->likes_count > 0 ? '❤️' : '🤍' }}</span>
                                        <span class="like-count">{{ $comment->likes_count }} likes</span>
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
                                <button class="see-less-btn hidden" data-post-id="{{ $post->id }}">See less</button>
                            </div>
                        @endif
                    </div>

                </article>
            @empty
                <p>No posts yet.</p>
            @endforelse
            <div class="pagination">
                {{ $posts->links() }}
            </div>
        </section>
    </main>
@endsection
