@foreach ($comments as $comment)
    <div class="comment" id="comment-{{ $comment->id }}">
        <span class="username">{{ $comment->user->name ?? 'Unknown User' }}</span>:
        <span class="comment-text">{{ $comment->comment }}</span>
        <div class="comment-meta">
            <small class="timestamp">{{ $comment->created_at->diffForHumans(['parts' => 2, 'join' => ',', 'short' => true]) }}</small>
            <span class="heart">{{ $comment->likes_count > 0 ? '❤️' : '🤍' }}</span>
            <span class="like-count">{{ $comment->likes_count }} likes</span>
        </div>
    </div>
@endforeach