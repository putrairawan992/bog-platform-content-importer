@extends('layouts.app')

@section('title', 'Home - Blogify')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-start">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Blogify</h1>
            {{-- <p class="text-lg text-gray-600">A simple blog platform with content importer from external APIs</p> --}}
        </div>

        @if ($posts->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No published posts yet</h3>
                <p class="mt-1 text-gray-500">Get started by creating a post or importing content from external APIs.</p>
                {{-- <div class="mt-6 space-x-4">
                    @auth
                        <a href="{{ route('admin.posts.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Create Post
                        </a>
                        <a href="{{ route('admin.import.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Import Content
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Login to Get Started
                        </a>
                    @endauth
                </div> --}}
            </div>
        @else
            <!-- Blog Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($posts as $post)
                    <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                        @if ($post->image)
                            <div class="aspect-video w-full overflow-hidden bg-gray-100">
                                <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <time datetime="{{ $post->created_at->toDateString() }}">
                                    {{ $post->created_at->format('F j, Y') }}
                                </time>
                                @if ($post->source && $post->source !== 'manual')
                                    <span class="mx-2">•</span>
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">
                                        From {{ ucfirst($post->source) }}
                                    </span>
                                @endif
                            </div>

                            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                                <a href="{{ route('post.show', $post) }}" class="hover:text-blue-600">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ Str::limit($post->content, 200) }}
                            </p>

                            <a href="{{ route('post.show', $post) }}"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                Read more
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
