<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('index', [
            'posts' => $posts
        ]);
    }

    public function showForm()
    {
        $post = new BlogPost();
        return view('blogpost-form', compact('post'));
    }

    public function savePost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:blog_posts|max:255',
            'content' => 'required',
        ]);

        $post = new BlogPost();
        $post->user_id = Auth::guard()->user()->id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return redirect('/');
    }

    public function editPost($id)
    {
        $post = BlogPost::findOrFail($id);

        return view('blogpost-form', compact('post'));
    }

    public function updatePost($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:blog_posts|max:255',
            'content' => 'required',
        ]);

        $post = BlogPost::findOrFail($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return redirect('/');
    }

    public function deletePost($id)
    {
        BlogPost::destroy($id);

        return redirect('/');
    }
}
