<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\{Attendance, Fee, Notification};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $parent = Auth::user()->parent;
        if (!$parent) {
            return redirect()->route('login')->with('error', 'Parent profile not found.');
        }
        $parent->load(['students.user', 'students.class', 'students.section']);

        $notifications = Notification::where(function ($q) {
            $q->whereNull('target_role')->orWhere('target_role', 'parent');
        })->latest()->limit(5)->get();

        return view('parent.dashboard', compact('parent', 'notifications'));
    }

    public function children()
    {
        $parent   = Auth::user()->parent;
        $children = $parent->students()->with(['user', 'class', 'section', 'fees'])->get();
        return view('parent.children', compact('parent', 'children'));
    }

    public function childAttendance($studentId)
    {
        $parent  = Auth::user()->parent;
        $student = $parent->students()->findOrFail($studentId);
        $student->load(['user', 'class']);

        $attendances = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')->paginate(30);

        $stats = [
            'total'   => Attendance::where('student_id', $student->id)->count(),
            'present' => Attendance::where('student_id', $student->id)->where('status', 'present')->count(),
            'absent'  => Attendance::where('student_id', $student->id)->where('status', 'absent')->count(),
        ];

        return view('parent.attendance', compact('student', 'attendances', 'stats'));
    }

    public function childFees($studentId)
    {
        $parent  = Auth::user()->parent;
        $student = $parent->students()->findOrFail($studentId);
        $fees    = Fee::where('student_id', $student->id)
            ->with(['category', 'transactions'])->latest()->get();

        return view('parent.fees', compact('student', 'fees'));
    }
}
