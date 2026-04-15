@extends('layouts.app')
@section('title', 'Edit Teacher')
@section('page-title', 'Edit Teacher')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="form-card">
    <h5 class="fw-bold mb-4">Edit Teacher: {{ $teacher->user->name }}</h5>
    <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $teacher->user->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email', $teacher->user->email) }}" required></div>
            <div class="col-md-4"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $teacher->user->phone) }}"></div>
            <div class="col-md-4"><label class="form-label">Qualification</label><input type="text" name="qualification" class="form-control" value="{{ old('qualification', $teacher->qualification) }}"></div>
            <div class="col-md-4"><label class="form-label">Specialization</label><input type="text" name="specialization" class="form-control" value="{{ old('specialization', $teacher->specialization) }}"></div>
            <div class="col-md-4"><label class="form-label">Joining Date</label><input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', $teacher->joining_date?->format('Y-m-d')) }}"></div>
            <div class="col-md-4"><label class="form-label">Salary (₹)</label><input type="number" name="salary" class="form-control" value="{{ old('salary', $teacher->salary) }}"></div>
            <div class="col-md-4"><label class="form-label">Emergency Contact</label><input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $teacher->emergency_contact) }}"></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Update</button>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection
