<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\{Attendance, Fee, Homework, Notification};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $student->load(['class', 'section', 'user']);

        // Calculate attendance stats
        $totalDays   = Attendance::where('student_id', $student->id)
            ->whereYear('date', date('Y'))->count();
        $presentDays = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->whereYear('date', date('Y'))->count();
        $attendancePercent = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        // Fee stats
        $totalFees   = Fee::where('student_id', $student->id)->sum('amount');
        $paidFees    = Fee::where('student_id', $student->id)->sum('paid_amount');
        $pendingFees = Fee::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'partial'])->sum('balance');

        // Recent homework
        $homeworks = Homework::where('class_id', $student->class_id)
            ->where('is_active', true)
            ->orderBy('due_date')->limit(5)->get();

        // Notifications
        $notifications = Notification::where(function ($q) use ($student) {
            $q->whereNull('target_role')
              ->orWhere('target_role', 'student')
              ->orWhere('user_id', $student->user_id);
        })->latest()->limit(5)->get();

        return view('student.dashboard', compact(
            'student', 'totalDays', 'presentDays', 'attendancePercent',
            'totalFees', 'paidFees', 'pendingFees', 'homeworks', 'notifications'
        ));
    }

    public function profile()
    {
        $student = Auth::user()->student;
        $student->load(['user', 'class', 'section']);
        return view('student.profile', compact('student'));
    }

    public function attendance()
    {
        $student     = Auth::user()->student;
        $attendances = Attendance::where('student_id', $student->id)
            ->with('subject')
            ->orderBy('date', 'desc')
            ->paginate(30);

        $stats = [
            'total'   => $attendances->total(),
            'present' => Attendance::where('student_id', $student->id)->where('status', 'present')->count(),
            'absent'  => Attendance::where('student_id', $student->id)->where('status', 'absent')->count(),
        ];

        return view('student.attendance', compact('student', 'attendances', 'stats'));
    }

    public function fees()
    {
        $student = Auth::user()->student;
        $fees    = Fee::where('student_id', $student->id)
            ->with(['category', 'transactions'])
            ->latest()->get();

        return view('student.fees', compact('student', 'fees'));
    }
}
