<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\{BlogPost, Setting};
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Show the public home page.
     */
    public function index()
    {
        $posts = BlogPost::where('status', 'published')
            ->latest('published_at')->limit(3)->get();
        return view('public.home', compact('posts'));
    }

    /**
     * Show a blog post.
     */
    public function blogPost(BlogPost $post)
    {
        if ($post->status !== 'published') {
            abort(404);
        }
        $recent = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->latest()->limit(5)->get();
        return view('public.blog.show', compact('post', 'recent'));
    }

    /**
     * Blog listing page (for SEO/content marketing).
     */
    public function blog()
    {
        $posts = BlogPost::where('status', 'published')
            ->latest('published_at')->paginate(10);
        return view('public.blog.index', compact('posts'));
    }

    /**
     * Contact page.
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Generate XML sitemap for SEO.
     */
    public function sitemap()
    {
        $posts = BlogPost::where('status', 'published')->get();
        $content = view('public.sitemap', compact('posts'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Serve robots.txt for SEO.
     */
    public function robots()
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /student/\nDisallow: /teacher/\nDisallow: /parent/\n\nSitemap: " . url('/sitemap.xml');
        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
