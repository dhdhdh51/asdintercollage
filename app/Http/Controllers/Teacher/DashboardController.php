<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\{Attendance, Homework, Notification, SchoolClass, Section, Student, Subject};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Teacher profile not found.');
        }
        $teacher->load(['classSubjects.class', 'classSubjects.subject', 'classSubjects.section']);

        $assignedClasses  = $teacher->classSubjects->pluck('class_id')->unique()->count();
        $assignedSubjects = $teacher->classSubjects->pluck('subject_id')->unique()->count();

        $recentHomeworks = Homework::where('teacher_id', $teacher->id)
            ->with(['class', 'subject'])->latest()->limit(5)->get();

        $notifications = Notification::where(function ($q) {
            $q->whereNull('target_role')->orWhere('target_role', 'teacher');
        })->latest()->limit(5)->get();

        return view('teacher.dashboard', compact(
            'teacher', 'assignedClasses', 'assignedSubjects', 'recentHomeworks', 'notifications'
        ));
    }

    public function attendance(Request $request)
    {
        $teacher  = Auth::user()->teacher;
        $assignments = $teacher->classSubjects()->with(['class', 'section', 'subject'])->get();

        $students = collect();
        $date     = $request->date ?? today()->format('Y-m-d');
        $selectedClass = $request->class_id;

        if ($selectedClass) {
            $students = Student::with(['user', 'section'])
                ->where('class_id', $selectedClass)
                ->where('is_active', true)->get();

            $existingAttendance = Attendance::where('class_id', $selectedClass)
                ->whereDate('date', $date)->get()->keyBy('student_id');
        } else {
            $existingAttendance = collect();
        }

        return view('teacher.attendance', compact(
            'teacher', 'assignments', 'students', 'date', 'selectedClass', 'existingAttendance'
        ));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'date'       => 'required|date|before_or_equal:today',
            'attendance' => 'required|array',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $request->date],
                [
                    'class_id'  => $request->class_id,
                    'status'    => $status,
                    'marked_by' => auth()->id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance saved successfully.');
    }

    public function homeworks()
    {
        $teacher   = Auth::user()->teacher;
        $homeworks = Homework::where('teacher_id', $teacher->id)
            ->with(['class', 'subject'])->latest()->paginate(15);
        $classes   = $teacher->classSubjects()->with(['class', 'subject'])->get();
        return view('teacher.homeworks', compact('teacher', 'homeworks', 'classes'));
    }

    public function storeHomework(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'class_id'   => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date'   => 'required|date|after:today',
        ]);

        $teacher   = Auth::user()->teacher;
        $filePath  = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('public/homeworks');
            $filePath = Storage::url($filePath);
        }

        Homework::create([
            'title'       => $request->title,
            'description' => $request->description,
            'class_id'    => $request->class_id,
            'section_id'  => $request->section_id,
            'subject_id'  => $request->subject_id,
            'teacher_id'  => $teacher->id,
            'due_date'    => $request->due_date,
            'file_path'   => $filePath,
        ]);

        return redirect()->back()->with('success', 'Homework uploaded successfully.');
    }
}
