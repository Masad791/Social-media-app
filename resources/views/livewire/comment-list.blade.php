<div class="mt-4">
    <h3 class="text-lg font-semibold mb-2">Comments ({{ $comments->total() }})</h3>

    <div class="space-y-3">
        @foreach($comments as $comment)
            <div class="bg-white p-3 rounded-lg shadow" wire:key="comment-{{ $comment->id }}">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="font-semibold text-gray-800">{{ $comment->user ? $comment->user->name : 'Unknown User' }}</span>
                        <p class="text-gray-600">{{ $comment->content }}</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $comment->created_at->diffForHumans() }}
                        @auth
                            @if(auth()->id() === $comment->user_id)
                                <button wire:click="deleteComment({{ $comment->id }})" wire:loading.attr="disabled" class="text-red-500 hover:text-red-700 ml-2">
                                    Delete
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($comments->hasMorePages())
        <button wire:click="$set('page', {{ $comments->currentPage() + 1 }})" wire:loading.attr="disabled" class="mt-3 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            See More Comments
        </button>
    @endif

    @auth
        <h4 class="text-md font-semibold mt-4 mb-2">Add a Comment</h4>
        <form wire:submit.prevent="addComment">
            <textarea wire:model="content" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Write your comment..."></textarea>
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <button type="submit" wire:loading.attr="disabled" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Submit
            </button>
        </form>
    @endauth
</div>