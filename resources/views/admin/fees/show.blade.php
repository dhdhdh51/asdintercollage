@extends('layouts.app')
@section('title', 'Fee Details')
@section('page-title', 'Fee Details')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="form-card mb-3">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Invoice: <code>{{ $fee->invoice_number }}</code></h5>
                    <div class="text-muted small">{{ $fee->created_at->format('d M Y') }}</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.fees.invoice', $fee) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-download me-1"></i>PDF</a>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6"><div class="small text-muted">Student</div><div class="fw-semibold">{{ $fee->student->user->name }}</div><div class="text-muted small">{{ $fee->student->student_id }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Class</div><div>{{ $fee->student->class->name ?? 'N/A' }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Fee Category</div><div>{{ $fee->category->name }}</div></div>
                <div class="col-md-6"><div class="small text-muted">Month</div><div>{{ $fee->month ?? 'N/A' }}</div></div>
                <div class="col-md-3"><div class="small text-muted">Amount</div><div class="fw-bold">₹{{ number_format($fee->amount, 2) }}</div></div>
                <div class="col-md-3"><div class="small text-muted">Discount</div><div class="text-success">-₹{{ number_format($fee->discount, 2) }}</div></div>
                <div class="col-md-3"><div class="small text-muted">Fine</div><div class="text-danger">+₹{{ number_format($fee->fine, 2) }}</div></div>
                <div class="col-md-3"><div class="small text-muted">Balance</div><div class="fw-bold text-danger">₹{{ number_format($fee->balance, 2) }}</div></div>
            </div>
        </div>

        <!-- Transactions -->
        <div class="form-card">
            <h6 class="fw-semibold mb-3">Payment History</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Txn ID</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($fee->transactions as $txn)
                        <tr>
                            <td><code>{{ $txn->transaction_id }}</code></td>
                            <td>₹{{ number_format($txn->amount, 2) }}</td>
                            <td>{{ ucfirst($txn->payment_method) }}</td>
                            <td><span class="badge bg-{{ $txn->status === 'success' ? 'success' : 'danger' }}-subtle text-{{ $txn->status === 'success' ? 'success' : 'danger' }}">{{ ucfirst($txn->status) }}</span></td>
                            <td>{{ $txn->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted">No payments recorded</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($fee->status !== 'paid')
        <div class="form-card mb-3">
            <h6 class="fw-bold mb-3 text-success"><i class="bi bi-cash me-2"></i>Collect Cash Payment</h6>
            <form method="POST" action="{{ route('admin.fees.collect-cash', $fee) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small">Amount (Max: ₹{{ number_format($fee->balance, 2) }})</label>
                    <input type="number" name="amount" class="form-control" step="0.01"
                        min="1" max="{{ $fee->balance }}" value="{{ $fee->balance }}" required>
                </div>
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-check2 me-2"></i>Record Payment
                </button>
            </form>
        </div>
        @endif

        <div class="form-card">
            <h6 class="fw-bold mb-3">Fee Summary</h6>
            <div class="d-flex justify-content-between mb-2"><span class="small">Total Amount</span><strong>₹{{ number_format($fee->amount, 2) }}</strong></div>
            <div class="d-flex justify-content-between mb-2"><span class="small">Discount</span><span class="text-success">-₹{{ number_format($fee->discount, 2) }}</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="small">Fine</span><span class="text-danger">+₹{{ number_format($fee->fine, 2) }}</span></div>
            <div class="d-flex justify-content-between mb-2"><span class="small">Paid</span><span class="text-success">₹{{ number_format($fee->paid_amount, 2) }}</span></div>
            <hr>
            <div class="d-flex justify-content-between"><strong>Balance Due</strong><strong class="text-danger">₹{{ number_format($fee->balance, 2) }}</strong></div>
            <div class="mt-3">
                <span class="badge bg-{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'overdue' ? 'danger' : 'warning') }} w-100 py-2">
                    {{ strtoupper($fee->status) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
