@extends('layouts.app')
@section('title', 'Fee Details')
@section('page-title', "Fee Details - {{ $student->user->name }}")
@section('sidebar-menu')
    <a href="{{ route('parent.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('parent.children') }}" class="nav-link active"><i class="bi bi-people"></i> My Children</a>
@endsection
@section('content')
<h5 class="fw-bold mb-3">{{ $student->user->name }} - Fee Records</h5>
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Invoice</th><th>Category</th><th>Amount</th><th>Paid</th><th>Balance</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($fees as $fee)
                <tr>
                    <td><code>{{ $fee->invoice_number }}</code></td>
                    <td>{{ $fee->category->name }}</td>
                    <td>₹{{ number_format($fee->amount, 2) }}</td>
                    <td class="text-success">₹{{ number_format($fee->paid_amount, 2) }}</td>
                    <td class="text-danger">₹{{ number_format($fee->balance, 2) }}</td>
                    <td><span class="badge bg-{{ $fee->status === 'paid' ? 'success' : 'warning' }}-subtle text-{{ $fee->status === 'paid' ? 'success' : 'warning' }} badge-status">{{ ucfirst($fee->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No fee records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
