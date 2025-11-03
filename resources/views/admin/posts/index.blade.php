@extends('layouts.app')

@section('title', 'Manage Posts - Blogify')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Manage Posts</h1>
            <a href="{{ route('admin.posts.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                Create New Post
            </a>
        </div>

        @if ($posts->isEmpty())
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 mb-4">No posts yet. Create your first post or import from external sources.</p>
                <div class="space-x-4">
                    <a href="{{ route('admin.posts.create') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                        Create Post
                    </a>
                    <a href="{{ route('admin.import.index') }}"
                        class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                        Import Content
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Source
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($posts as $post)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($post->image)
                                            <img src="{{ $post->image }}" alt="{{ $post->title }}"
                                                class="w-16 h-16 object-cover rounded mr-3">
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($post->content, 60) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ucfirst($post->source ?? 'N/A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $post->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this post?')"
                                            class="text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
