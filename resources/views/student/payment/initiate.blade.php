@extends('layouts.app')
@section('title', 'Pay Fee')
@section('page-title', 'Pay Fee via PayU')
@section('sidebar-menu')
    <a href="{{ route('student.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('student.fees') }}" class="nav-link active"><i class="bi bi-cash-stack"></i> Fee & Payments</a>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="form-card">
            <div class="text-center mb-4">
                <div style="width:64px;height:64px;background:#e0e7ff;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:#4f46e5;font-size:1.8rem;">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h5 class="fw-bold">Secure Payment</h5>
                <p class="text-muted small">You will be redirected to PayU secure payment gateway</p>
            </div>

            <div class="p-3 bg-light rounded mb-4">
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Fee Type</span><strong>{{ $fee->category->name }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Month/Year</span><strong>{{ $fee->month ?? $fee->academic_year }}</strong></div>
                <div class="d-flex justify-content-between"><span class="text-muted small">Amount to Pay</span><strong class="text-success fs-5">₹{{ $amount }}</strong></div>
            </div>

            <div class="alert alert-warning small">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Do not close this window or press back button during payment.
            </div>

            <!-- PayU Payment Form (auto-submits) -->
            <form id="payuForm" method="POST" action="{{ $payuUrl }}">
                <input type="hidden" name="key" value="{{ $merchantKey }}">
                <input type="hidden" name="txnid" value="{{ $txnId }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="hidden" name="productinfo" value="{{ $productInfo }}">
                <input type="hidden" name="firstname" value="{{ $firstName }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="phone" value="{{ $phone }}">
                <input type="hidden" name="surl" value="{{ $successUrl }}">
                <input type="hidden" name="furl" value="{{ $failureUrl }}">
                <input type="hidden" name="hash" value="{{ $hash }}">
                <input type="hidden" name="service_provider" value="payu_paisa">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-credit-card me-2"></i>Pay ₹{{ $amount }} Securely
                    </button>
                    <a href="{{ route('student.fees') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
