@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('sidebar-menu')
    <a href="{{ route('student.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('student.profile') }}" class="nav-link active"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('student.attendance') }}" class="nav-link"><i class="bi bi-calendar-check"></i> Attendance</a>
    <a href="{{ route('student.fees') }}" class="nav-link"><i class="bi bi-cash-stack"></i> Fee & Payments</a>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="form-card text-center">
            <div style="width:80px;height:80px;background:#4f46e5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:white;font-size:2rem;font-weight:700;">
                {{ strtoupper(substr($student->user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $student->user->name }}</h5>
            <code class="d-block mb-2">{{ $student->student_id }}</code>
            <span class="badge bg-primary">Class {{ $student->class->name ?? 'N/A' }} {{ $student->section ? '- Sec ' . $student->section->name : '' }}</span>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-card">
            <h6 class="fw-semibold mb-3">Personal Information</h6>
            <div class="row g-2">
                <div class="col-md-6"><div class="small text-muted">Full Name</div><div>{{ $student->user->name }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Email</div><div>{{ $student->user->email }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Phone</div><div>{{ $student->user->phone ?? 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Date of Birth</div><div>{{ $student->dob->format('d M Y') }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Gender</div><div>{{ ucfirst($student->gender) }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Blood Group</div><div>{{ $student->blood_group ?? 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Father Name</div><div>{{ $student->father_name }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Father Phone</div><div>{{ $student->father_phone ?? 'N/A' }}</div></div>
                <div class="col-12"><div class="small text-muted">Address</div><div>{{ $student->address }}, {{ $student->city }}, {{ $student->state }}</div></div>
            </div>
        </div>
    </div>
</div>
@endsection
