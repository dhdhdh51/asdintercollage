@extends('layouts.app')
@section('title', 'Teacher Details')
@section('page-title', 'Teacher Profile')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="form-card text-center">
            <div style="width:80px;height:80px;background:#06b6d4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:white;font-size:2rem;font-weight:700;">{{ strtoupper(substr($teacher->user->name, 0, 1)) }}</div>
            <h5 class="fw-bold mb-1">{{ $teacher->user->name }}</h5>
            <code class="d-block mb-1">{{ $teacher->employee_id }}</code>
            <p class="text-muted small">{{ $teacher->specialization ?? $teacher->qualification ?? 'Teacher' }}</p>
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary btn-sm w-100"><i class="bi bi-pencil me-1"></i>Edit Profile</a>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-card mb-3">
            <h6 class="fw-semibold mb-3">Professional Information</h6>
            <div class="row g-2">
                <div class="col-md-6"><div class="small text-muted">Qualification</div><div>{{ $teacher->qualification ?? 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Specialization</div><div>{{ $teacher->specialization ?? 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Joining Date</div><div>{{ $teacher->joining_date ? $teacher->joining_date->format('d M Y') : 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Salary</div><div>₹{{ number_format($teacher->salary, 2) }}/month</div></div>
                <div class="col-md-6"><div class="small text-muted">Phone</div><div>{{ $teacher->user->phone ?? 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Email</div><div>{{ $teacher->user->email }}</div></div>
            </div>
        </div>
        <div class="form-card">
            <h6 class="fw-semibold mb-3">Assigned Classes & Subjects</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Class</th><th>Section</th><th>Subject</th></tr></thead>
                    <tbody>
                        @forelse($teacher->classSubjects as $cs)
                        <tr><td>Class {{ $cs->class->name ?? 'N/A' }}</td><td>{{ $cs->section->name ?? 'N/A' }}</td><td>{{ $cs->subject->name ?? 'N/A' }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">No assignments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
