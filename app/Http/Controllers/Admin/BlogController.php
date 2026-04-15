<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('author')->latest()->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:200',
            'content'          => 'required|string',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($request->title);
        $count = BlogPost::where('slug', 'like', "$slug%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        BlogPost::create([
            'title'            => $request->title,
            'slug'             => $slug,
            'excerpt'          => $request->excerpt ?? Str::limit(strip_tags($request->content), 200),
            'content'          => $request->content,
            'meta_title'       => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description,
            'meta_keywords'    => $request->meta_keywords,
            'status'           => $request->status,
            'author_id'        => auth()->id(),
            'published_at'     => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $post)
    {
        return view('admin.blog.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'content' => 'required|string',
            'status'  => 'required|in:draft,published',
        ]);

        $post->update([
            'title'            => $request->title,
            'excerpt'          => $request->excerpt ?? Str::limit(strip_tags($request->content), 200),
            'content'          => $request->content,
            'meta_title'       => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description,
            'meta_keywords'    => $request->meta_keywords,
            'status'           => $request->status,
            'published_at'     => $request->status === 'published' && !$post->published_at ? now() : $post->published_at,
        ]);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();
        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post deleted.');
    }
}
