<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // show all posts
    public function index()
    {
        $posts = Post::latest()->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    // show create form
    public function create()
    {
        return view('admin.posts.create');
    }

    // store new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_file' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
            'status' => 'required|in:draft,published',
        ]);

        // handle image upload or url
        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('images', 'public');
            $imageUrl = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $imageUrl = $validated['image_url'];
        }

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imageUrl,
            'status' => $validated['status'],
            'source' => 'manual',
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully!');
    }

    // show edit form
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    // update post
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_file' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
            'status' => 'required|in:draft,published',
        ]);

        // handle image upload or url
        $imageUrl = $post->image;
        if ($request->hasFile('image_file')) {
            // delete old image if local file
            if ($post->image && str_starts_with($post->image, asset('storage/'))) {
                $oldPath = str_replace(asset('storage/'), '', $post->image);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image_file')->store('images', 'public');
            $imageUrl = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $imageUrl = $validated['image_url'];
        }

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imageUrl,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully!');
    }

    // delete post
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
