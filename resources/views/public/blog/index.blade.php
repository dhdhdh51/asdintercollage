@extends('layouts.public')
@section('title', 'Blog & Announcements')
@section('content')
<div class="container py-5">
    <h1 class="section-title text-center mb-5">Blog & Announcements</h1>
    <div class="row g-4">
        @forelse($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                @if($post->image)<img src="{{ $post->image }}" class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $post->title }}">@endif
                <div class="card-body">
                    <h5 class="card-title fw-bold"><a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">{{ $post->title }}</a></h5>
                    <p class="card-text text-muted small">{{ $post->excerpt }}</p>
                </div>
                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                    <small class="text-muted">{{ $post->published_at?->format('d M Y') }}</small>
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-primary">Read More</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">No blog posts yet.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $posts->links() }}</div>
</div>
@endsection
