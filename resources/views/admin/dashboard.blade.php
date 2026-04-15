@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('sidebar-menu')
    <div class="nav-section-title">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="nav-section-title">Academics</div>
    <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Students
    </a>
    <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> Teachers
    </a>
    <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
        <i class="bi bi-grid"></i> Classes & Sections
    </a>
    <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> Attendance
    </a>

    <div class="nav-section-title">Admissions</div>
    <a href="{{ route('admin.admissions.index') }}" class="nav-link {{ request()->routeIs('admin.admissions.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-person"></i> Admissions
        @if(isset($pendingCount) && $pendingCount > 0)
            <span class="badge-count">{{ $pendingCount }}</span>
        @endif
    </a>

    <div class="nav-section-title">Finance</div>
    <a href="{{ route('admin.fees.index') }}" class="nav-link {{ request()->routeIs('admin.fees.*') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i> Fee Management
    </a>
    <a href="{{ route('admin.fees.transactions') }}" class="nav-link">
        <i class="bi bi-credit-card"></i> Transactions
    </a>

    <div class="nav-section-title">Communication</div>
    <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
        <i class="bi bi-bell"></i> Notifications
    </a>
    <a href="{{ route('admin.blog.index') }}" class="nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
        <i class="bi bi-newspaper"></i> Blog / News
    </a>

    <div class="nav-section-title">System</div>
    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="bi bi-gear"></i> Settings
    </a>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:rgba(79,70,229,0.1);color:#4f46e5;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-card-value">{{ number_format($stats['total_students']) }}</div>
                <div class="stat-card-label">Total Students</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:rgba(6,182,212,0.1);color:#06b6d4;">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <div class="stat-card-value">{{ number_format($stats['total_teachers']) }}</div>
                <div class="stat-card-label">Teachers</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:rgba(16,185,129,0.1);color:#10b981;">
                <i class="bi bi-currency-rupee"></i>
            </div>
            <div>
                <div class="stat-card-value">₹{{ number_format($stats['monthly_revenue'], 0, '.', ',') }}</div>
                <div class="stat-card-label">Monthly Revenue</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b;">
                <i class="bi bi-file-earmark-person-fill"></i>
            </div>
            <div>
                <div class="stat-card-value">{{ number_format($stats['pending_admissions']) }}</div>
                <div class="stat-card-label">Pending Admissions</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-6">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:rgba(239,68,68,0.1);color:#ef4444;">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div>
                <div class="stat-card-value">₹{{ number_format($stats['pending_fees'], 0, '.', ',') }}</div>
                <div class="stat-card-label">Pending Fees</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:rgba(16,185,129,0.1);color:#10b981;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-card-value">₹{{ number_format($stats['total_revenue'], 0, '.', ',') }}</div>
                <div class="stat-card-label">Total Revenue</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:rgba(79,70,229,0.1);color:#4f46e5;">
                <i class="bi bi-calendar2-check-fill"></i>
            </div>
            <div>
                <div class="stat-card-value">{{ number_format($stats['todays_attendance']) }}</div>
                <div class="stat-card-label">Today's Attendance</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="stat-card">
            <div class="stat-card-icon" style="background:rgba(6,182,212,0.1);color:#06b6d4;">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="stat-card-value">{{ number_format($stats['total_parents']) }}</div>
                <div class="stat-card-label">Parents Registered</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="table-card-title"><i class="bi bi-bar-chart me-2"></i>Revenue Overview (Last 6 Months)</h6>
            </div>
            <div class="p-3">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Admissions -->
    <div class="col-lg-4">
        <div class="table-card h-100">
            <div class="table-card-header">
                <h6 class="table-card-title"><i class="bi bi-file-earmark-person me-2"></i>Recent Admissions</h6>
                <a href="{{ route('admin.admissions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recent_admissions as $admission)
                <a href="{{ route('admin.admissions.show', $admission) }}" class="list-group-item list-group-item-action py-2 px-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold small">{{ $admission->student_name }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                {{ $admission->application_id }} • Class {{ $admission->class->name ?? 'N/A' }}
                            </div>
                        </div>
                        <span class="badge bg-{{ $admission->status_badge }}-subtle text-{{ $admission->status_badge }} badge-status">
                            {{ ucfirst($admission->status) }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="p-3 text-center text-muted small">No admissions yet</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-12">
        <div class="table-card">
            <div class="table-card-header">
                <h6 class="table-card-title"><i class="bi bi-credit-card me-2"></i>Recent Transactions</h6>
                <a href="{{ route('admin.fees.transactions') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_transactions as $txn)
                        <tr>
                            <td><code>{{ $txn->transaction_id }}</code></td>
                            <td>{{ $txn->student->user->name ?? 'N/A' }}</td>
                            <td class="fw-semibold">₹{{ number_format($txn->amount, 2) }}</td>
                            <td><span class="badge bg-light text-dark">{{ ucfirst($txn->payment_method) }}</span></td>
                            <td>
                                <span class="badge bg-{{ $txn->status === 'success' ? 'success' : ($txn->status === 'failed' ? 'danger' : 'warning') }}-subtle text-{{ $txn->status === 'success' ? 'success' : ($txn->status === 'failed' ? 'danger' : 'warning') }} badge-status">
                                    {{ ucfirst($txn->status) }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $txn->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No transactions yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart');
const labels = @json($monthly_revenue->map(fn($r) => date('M Y', mktime(0, 0, 0, $r->month, 1, $r->year))));
const data   = @json($monthly_revenue->pluck('total'));

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels.length ? labels : ['No Data'],
        datasets: [{
            label: 'Revenue (₹)',
            data: data.length ? data : [0],
            backgroundColor: 'rgba(79, 70, 229, 0.8)',
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: { callback: v => '₹' + v.toLocaleString() }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
