@extends('layouts.app')
@section('title', 'Fee Management')
@section('page-title', 'Fee Management')
@section('sidebar-menu') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="row g-2 mb-3">
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-cash"></i></div><div><div class="stat-card-value">₹{{ number_format($stats['total_collected']) }}</div><div class="stat-card-label">Collected</div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-hourglass"></i></div><div><div class="stat-card-value">₹{{ number_format($stats['total_pending']) }}</div><div class="stat-card-label">Pending</div></div></div></div>
    <div class="col-4"><div class="stat-card"><div class="stat-card-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-exclamation-circle"></i></div><div><div class="stat-card-value">{{ $stats['total_overdue'] }}</div><div class="stat-card-label">Overdue</div></div></div></div>
</div>

<div class="page-header">
    <h4 class="page-title">Fee Records</h4>
    <a href="{{ route('admin.fees.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Create Fee</a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Invoice</th><th>Student</th><th>Category</th><th>Amount</th><th>Paid</th><th>Balance</th><th>Due Date</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($fees as $fee)
                <tr>
                    <td><code>{{ $fee->invoice_number }}</code></td>
                    <td>
                        <div class="fw-semibold small">{{ $fee->student->user->name ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">{{ $fee->student->student_id ?? '' }}</div>
                    </td>
                    <td>{{ $fee->category->name ?? 'N/A' }}</td>
                    <td class="fw-semibold">₹{{ number_format($fee->amount, 2) }}</td>
                    <td class="text-success">₹{{ number_format($fee->paid_amount, 2) }}</td>
                    <td class="text-danger">₹{{ number_format($fee->balance, 2) }}</td>
                    <td class="small {{ $fee->due_date->isPast() && $fee->status !== 'paid' ? 'text-danger' : '' }}">
                        {{ $fee->due_date->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'overdue' ? 'danger' : ($fee->status === 'partial' ? 'info' : 'warning')) }}-subtle
                            text-{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'overdue' ? 'danger' : ($fee->status === 'partial' ? 'info' : 'warning')) }} badge-status">
                            {{ ucfirst($fee->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.fees.show', $fee) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.fees.invoice', $fee) }}" class="btn btn-sm btn-light" title="Download Invoice"><i class="bi bi-download"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-4 text-muted">No fee records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 py-2 border-top">{{ $fees->withQueryString()->links() }}</div>
</div>
@endsection
