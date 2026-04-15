@extends('layouts.app')
@section('title', 'Blog Posts')
@section('page-title', 'Blog & Announcements')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">Blog Posts</h4>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Post</a>
</div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Title</th><th>Author</th><th>Status</th><th>Published</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td class="fw-semibold">{{ $post->title }}</td>
                    <td>{{ $post->author->name }}</td>
                    <td><span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning text-dark' }}-subtle text-{{ $post->status === 'published' ? 'success' : 'warning' }} badge-status">{{ ucfirst($post->status) }}</span></td>
                    <td class="text-muted small">{{ $post->published_at?->format('d M Y') ?? 'Draft' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No blog posts yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">{{ $posts->links() }}</div>
</div>
@endsection
