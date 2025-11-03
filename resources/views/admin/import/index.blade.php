@extends('layouts.app')

@section('title', 'Import Content - Blogify')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Import Content</h1>
            <p class="mt-2 text-gray-600">Import blog posts from external sources or manually via URL</p>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="switchTab('manual')" id="tab-manual"
                        class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                        Manual Import
                    </button>
                    <button onclick="switchTab('automatic')" id="tab-automatic"
                        class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Automatic Import
                    </button>
                </nav>
            </div>
        </div>

        <!-- Manual Import Tab Content -->
        <div id="content-manual" class="tab-content">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Import from URL</h3>
                <p class="text-gray-600 mb-6">Enter the API URL to import content. The system will attempt to transform the
                    response into a blog post.</p>

                <form action="{{ route('admin.import.manual') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- API URL -->
                    <div>
                        <label for="api_url" class="block text-sm font-medium text-gray-700 mb-2">API URL</label>
                        <input type="url" name="api_url" id="api_url" required
                            placeholder="https://api.example.com/posts/1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('api_url') border-red-500 @enderror">
                        @error('api_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">The source name will be automatically generated from the URL
                            domain</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Import from URL
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-blue-900 mb-2">Supported API Response Formats:</h4>
                <div class="text-blue-800 space-y-2 text-sm">
                    <p><strong>Format 1:</strong> <code class="bg-blue-100 px-1 rounded">{"{"} "title": "...", "body":
                            "...", "id": 1 {"}"}</code></p>
                    <p><strong>Format 2:</strong> <code class="bg-blue-100 px-1 rounded">{"{"} "title": "...",
                            "description": "...", "id": 1 {"}"}</code></p>
                    <p><strong>Format 3:</strong> <code class="bg-blue-100 px-1 rounded">{"{"} "title": "...", "content":
                            "...", "id": 1 {"}"}</code></p>
                    <p class="mt-3">The system will automatically detect and transform the response format.</p>
                </div>
            </div>
        </div>

        <!-- Automatic Import Tab Content -->
        <div id="content-automatic" class="tab-content hidden">
            <p class="text-gray-600 mb-6">Import blog posts from external sources. Each import will fetch a random item and
                save it as a draft.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- JSONPlaceholder Card -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">JSONPlaceholder</h3>
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded">Blog
                                Posts</span>
                        </div>
                        <p class="text-gray-600 mb-4">Import random blog posts from JSONPlaceholder API. Contains
                            ready-to-use blog content with titles and body text.</p>

                        <div class="bg-gray-50 rounded p-3 mb-4 overflow-x-scroll">
                            <p class="text-sm text-gray-700 font-mono">
                                https://jsonplaceholder.typicode.com/posts/{id}
                            </p>
                        </div>

                        <form action="{{ route('admin.import.jsonplaceholder') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                Import from JSONPlaceholder
                            </button>
                        </form>
                    </div>
                </div>

                <!-- FakeStore Card -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">FakeStore API</h3>
                            <span
                                class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">Products</span>
                        </div>
                        <p class="text-gray-600 mb-4">Import random products from FakeStore API. Product data will be
                            transformed into blog post format with pricing and category info.</p>

                        <div class="bg-gray-50 rounded p-3 mb-4">
                            <p class="text-sm text-gray-700 font-mono">
                                https://fakestoreapi.com/products/{id}
                            </p>
                        </div>

                        <form action="{{ route('admin.import.fakestore') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                Import from FakeStore
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-blue-900 mb-2">Important Notes:</h4>
                    <ul class="list-disc list-inside text-blue-800 space-y-1">
                        <li>Each import fetches a single random item</li>
                        <li>Imported content is automatically saved as a draft</li>
                        <li>Duplicate prevention is active - the same item won't be imported twice</li>
                        <li>You can edit and publish imported posts from the Admin panel</li>
                    </ul>
                </div>
            </div>

            <!-- Link to Admin -->
            <div class="mt-6 text-center">
                <a href="{{ route('admin.posts.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Posts Management
                </a>
            </div>
        </div>

        <script>
            function switchTab(tab) {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active state from all tab buttons
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('border-blue-500', 'text-blue-600');
                    button.classList.add('border-transparent', 'text-gray-500');
                });

                // Show selected tab content
                document.getElementById('content-' + tab).classList.remove('hidden');

                // Add active state to selected tab button
                const activeButton = document.getElementById('tab-' + tab);
                activeButton.classList.remove('border-transparent', 'text-gray-500');
                activeButton.classList.add('border-blue-500', 'text-blue-600');
            }
        </script>
    @endsection
