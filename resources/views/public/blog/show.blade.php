@extends('layouts.public')
@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description)
@section('meta_keywords', $post->meta_keywords)
@section('og_title', $post->title)
@section('og_description', $post->excerpt)
@section('og_image', $post->image)
@section('robots', 'index, follow')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <article>
                <h1 class="fw-bold display-5 mb-3">{{ $post->title }}</h1>
                <div class="text-muted small mb-4"><i class="bi bi-calendar3 me-2"></i>{{ $post->published_at?->format('d M Y') }} | <i class="bi bi-person me-2"></i>{{ $post->author->name }}</div>
                @if($post->image)<img src="{{ $post->image }}" class="img-fluid rounded mb-4" alt="{{ $post->title }}">@endif
                <div class="prose">{!! $post->content !!}</div>
            </article>
        </div>
        <div class="col-lg-4">
            <div class="card p-4">
                <h6 class="fw-bold mb-3">Recent Posts</h6>
                @foreach($recent as $p)
                <div class="mb-3">
                    <a href="{{ route('blog.show', $p->slug) }}" class="text-decoration-none text-dark fw-semibold small">{{ $p->title }}</a>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $p->published_at?->format('d M Y') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
