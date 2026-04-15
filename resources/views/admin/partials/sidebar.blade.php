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
