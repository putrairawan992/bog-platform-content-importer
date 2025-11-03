@extends('layouts.app')

@section('title', 'Create Post - Blogify')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title') }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                <textarea name="content"
                          id="content"
                          rows="10"
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image (Optional)</label>

                <!-- Tab Selector -->
                <div class="flex space-x-4 mb-3">
                    <button type="button" onclick="showImageTab('upload')" id="btn-upload"
                            class="px-3 py-1 text-sm font-medium text-blue-600 border-b-2 border-blue-600">
                        Upload File
                    </button>
                    <button type="button" onclick="showImageTab('url')" id="btn-url"
                            class="px-3 py-1 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700">
                        Image URL
                    </button>
                </div>

                <!-- Upload File Tab -->
                <div id="tab-upload" class="image-tab">
                    <input type="file"
                           name="image_file"
                           id="image_file"
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image_file') border-red-500 @enderror">
                    @error('image_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Supported: JPG, PNG, GIF (max 2MB)</p>
                </div>

                <!-- URL Tab -->
                <div id="tab-url" class="image-tab hidden">
                    <input type="url"
                           name="image_url"
                           id="image_url"
                           value="{{ old('image_url') }}"
                           placeholder="https://example.com/image.jpg"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image_url') border-red-500 @enderror">
                    @error('image_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <script>
            function showImageTab(tab) {
                // Hide all tabs
                document.querySelectorAll('.image-tab').forEach(el => el.classList.add('hidden'));

                // Remove active state from all buttons
                document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                    btn.classList.remove('text-blue-600', 'border-blue-600');
                    btn.classList.add('text-gray-500', 'border-transparent');
                });

                // Show selected tab
                document.getElementById('tab-' + tab).classList.remove('hidden');

                // Add active state to button
                const activeBtn = document.getElementById('btn-' + tab);
                activeBtn.classList.remove('text-gray-500', 'border-transparent');
                activeBtn.classList.add('text-blue-600', 'border-blue-600');
            }
            </script>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status"
                        id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.posts.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Post
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
