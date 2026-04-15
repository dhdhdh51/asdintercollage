@extends('layouts.app')
@section('title', 'My Children')
@section('page-title', 'My Children')
@section('sidebar-menu')
    <a href="{{ route('parent.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('parent.children') }}" class="nav-link active"><i class="bi bi-people"></i> My Children</a>
@endsection
@section('content')
<div class="row g-3">
    @foreach($children as $student)
    <div class="col-md-6">
        <div class="form-card">
            <div class="d-flex gap-3 mb-3">
                <div style="width:56px;height:56px;background:#4f46e5;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.3rem;">{{ strtoupper(substr($student->user->name,0,1)) }}</div>
                <div><div class="fw-bold">{{ $student->user->name }}</div><div class="text-muted small">{{ $student->student_id }} | Class {{ $student->class->name ?? 'N/A' }}</div></div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-4 text-center border rounded py-2"><div class="fw-bold text-success">{{ $student->fees->sum('paid_amount') > 0 ? '₹' . number_format($student->fees->sum('paid_amount')) : '₹0' }}</div><div class="small text-muted">Paid</div></div>
                <div class="col-4 text-center border rounded py-2"><div class="fw-bold text-danger">₹{{ number_format($student->fees->whereIn('status', ['pending','partial'])->sum('balance')) }}</div><div class="small text-muted">Pending</div></div>
                <div class="col-4 text-center border rounded py-2"><div class="fw-bold">{{ $student->fees->count() }}</div><div class="small text-muted">Invoices</div></div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('parent.children.attendance', $student) }}" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-calendar-check me-1"></i>Attendance</a>
                <a href="{{ route('parent.children.fees', $student) }}" class="btn btn-sm btn-outline-success flex-fill"><i class="bi bi-cash me-1"></i>Fee Details</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
