@extends('layouts.app')
@section('title', 'Edit Post')
@section('page-title', 'Edit Blog Post')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="form-card">
    <form method="POST" action="{{ route('admin.blog.update', $post) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-9"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" required value="{{ old('title', $post->title) }}"></div>
            <div class="col-md-3"><label class="form-label">Status *</label><select name="status" class="form-select"><option value="draft" {{ $post->status === 'draft' ? 'selected' : '' }}>Draft</option><option value="published" {{ $post->status === 'published' ? 'selected' : '' }}>Published</option></select></div>
            <div class="col-12"><label class="form-label">Excerpt</label><textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $post->excerpt) }}</textarea></div>
            <div class="col-12"><label class="form-label">Content *</label><textarea name="content" class="form-control" rows="12" required>{{ old('content', $post->content) }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title) }}"></div>
            <div class="col-md-6"><label class="form-label">Meta Keywords</label><input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $post->meta_keywords) }}"></div>
            <div class="col-12"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $post->meta_description) }}</textarea></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Update Post</button>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection
