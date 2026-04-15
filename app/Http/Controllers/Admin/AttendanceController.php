<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Attendance, SchoolClass, Section, Student, Subject};
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $classes  = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();
        $sections = Section::where('is_active', true)->get();
        $date     = $request->date ?? today()->format('Y-m-d');
        $classId  = $request->class_id;
        $sectionId = $request->section_id;

        $students = collect();
        $attendances = collect();

        if ($classId) {
            $query = Student::with(['user', 'section'])->where('class_id', $classId)->where('is_active', true);
            if ($sectionId) {
                $query->where('section_id', $sectionId);
            }
            $students = $query->get();

            $attendances = Attendance::where('class_id', $classId)
                ->whereDate('date', $date)
                ->when($sectionId, fn($q) => $q->where('section_id', $sectionId))
                ->get()
                ->keyBy('student_id');
        }

        return view('admin.attendance.index', compact(
            'classes', 'sections', 'students', 'attendances', 'date', 'classId', 'sectionId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'  => 'required|exists:classes,id',
            'date'      => 'required|date|before_or_equal:today',
            'attendance' => 'required|array',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date'       => $request->date,
                    'subject_id' => $request->subject_id ?? null,
                ],
                [
                    'class_id'   => $request->class_id,
                    'section_id' => $request->section_id ?? null,
                    'status'     => $status,
                    'marked_by'  => auth()->id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance saved successfully.');
    }

    public function report(Request $request)
    {
        $classes  = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();
        $data     = [];

        if ($request->filled('class_id') && $request->filled('month')) {
            $students = Student::with('user')
                ->where('class_id', $request->class_id)
                ->where('is_active', true)->get();

            $month = $request->month; // YYYY-MM
            [$year, $m] = explode('-', $month);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $year);

            foreach ($students as $student) {
                $attendanceData = Attendance::where('student_id', $student->id)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $m)
                    ->get()
                    ->keyBy(fn($a) => $a->date->format('d'));

                $present = $attendanceData->where('status', 'present')->count();
                $absent  = $attendanceData->where('status', 'absent')->count();
                $total   = $daysInMonth;
                $percent = $total > 0 ? round(($present / $total) * 100, 1) : 0;

                $data[] = [
                    'student'  => $student,
                    'present'  => $present,
                    'absent'   => $absent,
                    'total'    => $total,
                    'percent'  => $percent,
                    'details'  => $attendanceData,
                ];
            }
        }

        return view('admin.attendance.report', compact('classes', 'data'));
    }
}
