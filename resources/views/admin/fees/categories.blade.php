@extends('layouts.app')
@section('title', 'Fee Categories')
@section('page-title', 'Fee Categories')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="form-card">
            <h6 class="fw-bold mb-3">Add Category</h6>
            <form method="POST" action="{{ route('admin.fees.categories.store') }}">
                @csrf
                <div class="mb-3"><label class="form-label small">Category Name *</label><input type="text" name="name" class="form-control" required placeholder="e.g. Tuition Fee, Transport Fee"></div>
                <div class="mb-3"><label class="form-label small">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                <button type="submit" class="btn btn-primary w-100">Add Category</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="table-card">
            <div class="table-card-header"><h6 class="table-card-title">Fee Categories</h6></div>
            <table class="table mb-0">
                <thead><tr><th>Name</th><th>Total Fees</th></tr></thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr><td class="fw-semibold">{{ $cat->name }}</td><td>{{ $cat->fees_count }}</td></tr>
                    @empty
                    <tr><td colspan="2" class="text-center text-muted py-3">No categories</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
