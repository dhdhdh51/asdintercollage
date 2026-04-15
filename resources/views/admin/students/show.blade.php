@extends('layouts.app')
@section('title', 'Student Details')
@section('page-title', 'Student Profile')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="form-card text-center">
            <div style="width:80px;height:80px;background:#4f46e5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:white;font-size:2rem;font-weight:700;">
                {{ strtoupper(substr($student->user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $student->user->name }}</h5>
            <p class="text-muted small mb-2">{{ $student->user->email }}</p>
            <span class="badge bg-primary mb-3">{{ $student->student_id }}</span>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit Profile
                </a>
            </div>
        </div>

        <div class="form-card mt-3">
            <h6 class="fw-semibold mb-3">Academic Info</h6>
            <table class="table table-sm mb-0">
                <tr><td class="text-muted small">Class</td><td class="fw-semibold">{{ $student->class->name ?? 'N/A' }}</td></tr>
                <tr><td class="text-muted small">Section</td><td>{{ $student->section->name ?? 'N/A' }}</td></tr>
                <tr><td class="text-muted small">Roll No</td><td>{{ $student->roll_number ?? 'N/A' }}</td></tr>
                <tr><td class="text-muted small">Adm. Year</td><td>{{ $student->admission_year }}</td></tr>
                <tr><td class="text-muted small">Gender</td><td>{{ ucfirst($student->gender) }}</td></tr>
                <tr><td class="text-muted small">DOB</td><td>{{ $student->dob->format('d M Y') }}</td></tr>
                <tr><td class="text-muted small">Blood Group</td><td>{{ $student->blood_group ?? 'N/A' }}</td></tr>
            </table>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Fee Summary -->
        <div class="form-card mb-3">
            <h6 class="fw-semibold mb-3"><i class="bi bi-cash-stack me-2"></i>Fee Summary</h6>
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <div class="p-2 border rounded text-center">
                        <div class="fw-bold text-success">₹{{ number_format($student->fees->sum('paid_amount'), 2) }}</div>
                        <div class="small text-muted">Paid</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 border rounded text-center">
                        <div class="fw-bold text-danger">₹{{ number_format($student->fees->sum('balance'), 2) }}</div>
                        <div class="small text-muted">Pending</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 border rounded text-center">
                        <div class="fw-bold">{{ $student->fees->count() }}</div>
                        <div class="small text-muted">Invoices</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance -->
        <div class="form-card mb-3">
            <h6 class="fw-semibold mb-2"><i class="bi bi-calendar-check me-2"></i>Recent Attendance</h6>
            @php
                $totalDays = $student->attendances->count();
                $presentDays = $student->attendances->where('status', 'present')->count();
                $percent = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
            @endphp
            <div class="d-flex gap-3 align-items-center mb-2">
                <div class="text-center"><div class="fw-bold">{{ $totalDays }}</div><div class="small text-muted">Total</div></div>
                <div class="text-center"><div class="fw-bold text-success">{{ $presentDays }}</div><div class="small text-muted">Present</div></div>
                <div class="text-center"><div class="fw-bold text-danger">{{ $totalDays - $presentDays }}</div><div class="small text-muted">Absent</div></div>
                <div class="flex-fill">
                    <div class="d-flex justify-content-between small mb-1"><span>Attendance</span><strong>{{ $percent }}%</strong></div>
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar {{ $percent >= 75 ? 'bg-success' : 'bg-danger' }}" style="width:{{ $percent }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family Info -->
        <div class="form-card">
            <h6 class="fw-semibold mb-3"><i class="bi bi-people me-2"></i>Family Information</h6>
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="small text-muted">Father Name</div>
                    <div class="fw-semibold">{{ $student->father_name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Father Phone</div>
                    <div>{{ $student->father_phone ?? 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Mother Name</div>
                    <div>{{ $student->mother_name ?? 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Mother Phone</div>
                    <div>{{ $student->mother_phone ?? 'N/A' }}</div>
                </div>
                <div class="col-12 mt-2">
                    <div class="small text-muted">Address</div>
                    <div>{{ $student->address }}, {{ $student->city }}, {{ $student->state }} {{ $student->pincode }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
