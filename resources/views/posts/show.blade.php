<x-app-layout>
    <x-slot name="header">
    {{ __('Postinger') }}
</x-slot>
<div class="show-post-container max-w-3xl mx-auto p-4 sm:p-6 md:p-8">
    <section class="post-details">

        <div class="post-card bg-white rounded-lg shadow-md p-4 sm:p-6">

            @if ($post->image)
                <div class="post-image mb-4">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image"
                        class="rounded-lg shadow-md max-w-full h-auto">
                </div>
            @endif

            <p class="text-sm text-gray-600">
                Posted by <span class="font-semibold">{{ $post->user->name}}</span>
                on {{ $post->created_at->format('F j, Y g:i A') }}
            </p>
        </div>
        
        <div class="mt-6">
            <a href="{{ route('posts.index') }}"
                class="px-3 py-2 border-2 border-transparent btn-back inline-block px-4 py-3 bg-[#142f39] text-white rounded  hover:text-[#142f39] hover:border-[#142f39] hover:bg-transparent 
         transition-colors duration-200">
                ← Back to Posts
            </a>
        </div>
    </section>
</div>
</x-app-layout>
