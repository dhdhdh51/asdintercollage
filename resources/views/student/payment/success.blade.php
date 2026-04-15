@extends('layouts.app')
@section('title', 'Payment Successful')
@section('page-title', 'Payment Successful')
@section('sidebar-menu')
    <a href="{{ route('student.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('student.fees') }}" class="nav-link active"><i class="bi bi-cash-stack"></i> Fee & Payments</a>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="form-card text-center">
            <div style="width:80px;height:80px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:#16a34a;font-size:2.5rem;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h4 class="fw-bold text-success mb-2">Payment Successful!</h4>
            <p class="text-muted">Your fee payment has been processed successfully.</p>

            <div class="p-3 bg-light rounded mt-3 mb-4 text-start">
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Transaction ID</span><code>{{ $transaction->transaction_id }}</code></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Amount Paid</span><strong class="text-success">₹{{ number_format($transaction->amount, 2) }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Receipt No</span><strong>{{ $transaction->receipt_number }}</strong></div>
                <div class="d-flex justify-content-between"><span class="text-muted small">Date</span><strong>{{ $transaction->created_at->format('d M Y H:i') }}</strong></div>
            </div>

            <p class="text-muted small mb-4">A confirmation email has been sent to your registered email address.</p>
            <a href="{{ route('student.fees') }}" class="btn btn-primary">View All Fees</a>
        </div>
    </div>
</div>
@endsection
