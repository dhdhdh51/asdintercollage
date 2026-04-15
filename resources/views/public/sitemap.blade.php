<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <!-- Static Pages -->
    <url><loc>{{ url('/') }}</loc><changefreq>daily</changefreq><priority>1.0</priority></url>
    <url><loc>{{ route('admission.form') }}</loc><changefreq>monthly</changefreq><priority>0.9</priority></url>
    <url><loc>{{ route('admission.status') }}</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('blog') }}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('contact') }}</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>

    <!-- Blog Posts -->
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->format('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>
