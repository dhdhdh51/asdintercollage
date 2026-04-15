<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Admission, Attendance, Fee, Student, Teacher, Transaction, User};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard with comprehensive analytics.
     */
    public function index()
    {
        // Key metrics
        $stats = [
            'total_students'    => Student::where('is_active', true)->count(),
            'total_teachers'    => Teacher::where('is_active', true)->count(),
            'total_parents'     => User::where('role', 'parent')->count(),
            'pending_admissions'=> Admission::where('status', 'pending')->count(),
            'total_revenue'     => Transaction::where('status', 'success')->sum('amount'),
            'monthly_revenue'   => Transaction::where('status', 'success')
                                    ->whereMonth('created_at', now()->month)
                                    ->sum('amount'),
            'pending_fees'      => Fee::where('status', 'pending')->sum('balance'),
            'todays_attendance' => Attendance::whereDate('date', today())->count(),
        ];

        // Recent admissions
        $recent_admissions = Admission::with('class')
            ->latest()->limit(5)->get();

        // Monthly revenue for chart (last 6 months)
        $monthly_revenue = Transaction::where('status', 'success')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        // Recent transactions
        $recent_transactions = Transaction::with(['student.user', 'fee.category'])
            ->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'recent_admissions', 'monthly_revenue', 'recent_transactions'
        ));
    }
}
