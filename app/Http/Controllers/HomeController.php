<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // show published posts
    public function index()
    {
        $posts = Post::published()
            ->latest()
            ->paginate(10);

        return view('home', compact('posts'));
    }

    // show single post
    public function show(Post $post)
    {
        if ($post->status !== 'published') {
            abort(404);
        }

        return view('post', compact('post'));
    }
}
