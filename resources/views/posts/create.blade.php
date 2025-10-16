<x-app-layout>
    <x-slot name="header">
        {{ __('New Post') }}
    </x-slot>

    <section class="post-creation">
        <h2>Create a New Post</h2>
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="post-form">
            @csrf
            <div class="form-group">
                <textarea name="text" placeholder="Share your thoughts..." rows="3" class="post-textarea" required></textarea>
                @error('text')
                    <p class="text-red-600 text-sm mt-1 bg-red-100 px-2 py-1 rounded-md">
                        ⚠️ {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="image" class="file-label">
                    <span class="file-icon"></span> Upload image
                </label>
                <input type="file" name="image" id="image" accept="image/*" class="file-input">
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- 🔹 Crop Preview -->
                <div id="preview-container" class="preview-container">
                    <img id="preview-image" src="" alt="">
                </div>

                <!-- Hidden field to store cropped image -->
                <input type="hidden" name="cropped_image" id="cropped-image">
            </div>
            <button type="submit" class="submit-btn">Share Post</button>
        </form>
    </section>
</x-app-layout>
