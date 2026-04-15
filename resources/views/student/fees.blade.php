@extends('layouts.app')
@section('title', 'Fee & Payments')
@section('page-title', 'Fee & Payments')
@section('sidebar-menu')
    <a href="{{ route('student.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('student.profile') }}" class="nav-link"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('student.attendance') }}" class="nav-link"><i class="bi bi-calendar-check"></i> Attendance</a>
    <a href="{{ route('student.fees') }}" class="nav-link active"><i class="bi bi-cash-stack"></i> Fee & Payments</a>
@endsection
@section('content')
<div class="table-card">
    <div class="table-card-header">
        <h6 class="table-card-title"><i class="bi bi-cash-stack me-2"></i>My Fee Records</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Invoice</th><th>Category</th><th>Amount</th><th>Paid</th><th>Balance</th><th>Due Date</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($fees as $fee)
                <tr>
                    <td><code>{{ $fee->invoice_number }}</code></td>
                    <td>{{ $fee->category->name }}</td>
                    <td>₹{{ number_format($fee->amount, 2) }}</td>
                    <td class="text-success">₹{{ number_format($fee->paid_amount, 2) }}</td>
                    <td class="text-danger fw-semibold">₹{{ number_format($fee->balance, 2) }}</td>
                    <td>{{ $fee->due_date->format('d M Y') }}</td>
                    <td><span class="badge bg-{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'overdue' ? 'danger' : 'warning') }}-subtle text-{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'overdue' ? 'danger' : 'warning') }} badge-status">{{ ucfirst($fee->status) }}</span></td>
                    <td>
                        @if($fee->status !== 'paid')
                        <a href="{{ route('student.fees.pay', $fee) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-credit-card me-1"></i>Pay Now
                        </a>
                        @else
                        <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Paid</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No fee records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
