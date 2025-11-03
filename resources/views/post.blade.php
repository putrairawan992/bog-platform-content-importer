@extends('layouts.app')

@section('title', $post->title . ' - Blogify')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>

        <!-- Post Content -->
        <article class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if ($post->image)
                <div class="w-full overflow-hidden bg-gray-100">
                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                </div>
            @endif
            <div class="p-8">
                <!-- Meta Info -->
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <time datetime="{{ $post->created_at->toDateString() }}">
                        {{ $post->created_at->format('F j, Y') }}
                    </time>
                    @if ($post->source && $post->source !== 'manual')
                        <span class="mx-2">•</span>
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">
                            Imported from {{ ucfirst($post->source) }}
                        </span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="text-4xl font-bold text-gray-900 mb-6">
                    {{ $post->title }}
                </h1>

                <!-- Content -->
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Last updated: {{ $post->updated_at->format('F j, Y') }}
                    </div>
                    {{-- @if ($post->source)
                        <div class="text-sm text-gray-500">
                            Source: {{ ucfirst($post->source) }}
                            @if ($post->external_id)
                                (ID: {{ $post->external_id }})
                            @endif
                        </div>
                    @endif --}}
                </div>
            </div>
        </article>

        <!-- Admin Actions (Optional) -->
        {{-- @auth
            <div class="mt-6 text-center">
                <a href="{{ route('admin.posts.edit', $post) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Edit this post
                </a>
            </div>
        @endauth --}}
    </div>
@endsection
