<x-app-layout>

    <x-slot name="header">
    {{ __('Edit Post') }}
</x-slot>

    <main class="container">
        <section class="post-creation">
            <h2>Edit Post</h2>
            <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="post-form">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <textarea name="text" placeholder="Share your thoughts..." rows="3" class="post-textarea" chnages-required>{{ old('text', $post->text) }}</textarea>
                    @error('text')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                {{-- <div class="form-group">
                    <label for="image" class="file-label">
                        <span class="file-icon">📷</span> Update Image
                    </label>
                    <input type="file" name="image" id="image" accept="image/*" class="file-input">
                    @error('image')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" alt="Current Image" class="post-image preview">
                    @endif
                </div> --}}
                <button type="submit" class="submit-btn">Update Post</button>
            </form>
        </section>
    </main>
</x-app-layout>