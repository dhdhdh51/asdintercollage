@extends('layouts.app')
@section('title', 'Payment Failed')
@section('page-title', 'Payment Failed')
@section('sidebar-menu')
    <a href="{{ route('student.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('student.fees') }}" class="nav-link active"><i class="bi bi-cash-stack"></i> Fee & Payments</a>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="form-card text-center">
            <div style="width:80px;height:80px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:#dc2626;font-size:2.5rem;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <h4 class="fw-bold text-danger mb-2">Payment Failed</h4>
            <p class="text-muted">Your payment could not be processed. No amount has been deducted.</p>
            <div class="p-3 bg-light rounded mt-3 mb-4">
                <div class="text-muted small">Reason: {{ $request->error_Message ?? 'Payment was declined or cancelled.' }}</div>
            </div>
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('student.fees') }}" class="btn btn-primary">Try Again</a>
                <a href="{{ route('student.dashboard') }}" class="btn btn-light">Go to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
