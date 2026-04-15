@extends('layouts.app')
@section('title', 'Create Fee')
@section('page-title', 'Create Fee Record')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="form-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-cash-stack me-2"></i>New Fee Record</h5>
    <form method="POST" action="{{ route('admin.fees.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Student *</label>
                <select name="student_id" class="form-select" required>
                    <option value="">Select Student...</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->user->name }} ({{ $student->student_id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fee Category *</label>
                <select name="fee_category_id" class="form-select" required>
                    <option value="">Select Category...</option>
                    @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Amount (₹) *</label>
                <input type="number" name="amount" class="form-control" step="0.01" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Discount (₹)</label>
                <input type="number" name="discount" class="form-control" step="0.01" min="0" value="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Fine/Late Fee (₹)</label>
                <input type="number" name="fine" class="form-control" step="0.01" min="0" value="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Due Date *</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Month</label>
                <input type="text" name="month" class="form-control" placeholder="e.g. April 2024">
            </div>
            <div class="col-md-4">
                <label class="form-label">Academic Year *</label>
                <input type="number" name="academic_year" class="form-control" value="{{ date('Y') }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-control" rows="2"></textarea>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Create Fee</button>
            <a href="{{ route('admin.fees.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection
