@extends('layouts.app')
@section('title', 'Transactions')
@section('page-title', 'Payment Transactions')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-2 mb-3">
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-cash"></i></div><div><div class="stat-card-value">₹{{ number_format($stats['total']) }}</div><div class="stat-card-label">Total Collected</div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="bi bi-calendar-day"></i></div><div><div class="stat-card-value">₹{{ number_format($stats['today']) }}</div><div class="stat-card-label">Today</div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-hourglass"></i></div><div><div class="stat-card-value">{{ $stats['pending'] }}</div><div class="stat-card-label">Pending</div></div></div></div>
</div>
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Transaction ID</th><th>Student</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($transactions as $txn)
                <tr>
                    <td><code>{{ $txn->transaction_id }}</code></td>
                    <td class="fw-semibold small">{{ $txn->student->user->name ?? 'N/A' }}</td>
                    <td class="fw-semibold">₹{{ number_format($txn->amount, 2) }}</td>
                    <td><span class="badge bg-light text-dark">{{ ucfirst($txn->payment_method) }}</span></td>
                    <td><span class="badge bg-{{ $txn->status === 'success' ? 'success' : ($txn->status === 'failed' ? 'danger' : 'warning') }}-subtle text-{{ $txn->status === 'success' ? 'success' : ($txn->status === 'failed' ? 'danger' : 'warning') }} badge-status">{{ ucfirst($txn->status) }}</span></td>
                    <td class="text-muted small">{{ $txn->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No transactions</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">{{ $transactions->links() }}</div>
</div>
@endsection
