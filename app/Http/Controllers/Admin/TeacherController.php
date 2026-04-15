<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{SchoolClass, Section, Subject, Teacher, TeacherClassSubject, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'classSubjects.class'])->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"))
                ->orWhere('employee_id', 'like', "%$search%");
        }

        $teachers = $query->paginate(20);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $classes  = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();
        $sections = Section::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.teachers.create', compact('classes', 'sections', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'nullable|string|max:15',
            'password'     => 'required|min:8|confirmed',
            'qualification'=> 'nullable|string|max:200',
            'joining_date' => 'nullable|date',
            'salary'       => 'nullable|numeric|min:0',
            'dob'          => 'nullable|date|before:today',
            'gender'       => 'nullable|in:male,female,other',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'teacher',
            ]);

            $year   = date('Y');
            $count  = Teacher::count() + 1;
            $empId  = 'EMP' . $year . str_pad($count, 4, '0', STR_PAD_LEFT);

            $teacher = Teacher::create([
                'user_id'         => $user->id,
                'employee_id'     => $empId,
                'qualification'   => $request->qualification,
                'specialization'  => $request->specialization,
                'joining_date'    => $request->joining_date,
                'salary'          => $request->salary ?? 0,
                'address'         => $request->address,
                'dob'             => $request->dob,
                'gender'          => $request->gender,
                'emergency_contact' => $request->emergency_contact,
            ]);

            // Assign classes/subjects
            if ($request->filled('class_subjects')) {
                foreach ($request->class_subjects as $assignment) {
                    if (!empty($assignment['class_id']) && !empty($assignment['subject_id'])) {
                        TeacherClassSubject::create([
                            'teacher_id' => $teacher->id,
                            'class_id'   => $assignment['class_id'],
                            'section_id' => $assignment['section_id'] ?? null,
                            'subject_id' => $assignment['subject_id'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'classSubjects.class', 'classSubjects.subject', 'classSubjects.section']);
        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $classes  = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();
        $sections = Section::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $teacher->load(['user', 'classSubjects']);
        return view('admin.teachers.edit', compact('teacher', 'classes', 'sections', 'subjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
        ]);

        DB::transaction(function () use ($request, $teacher) {
            $teacher->user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            $teacher->update($request->only([
                'qualification', 'specialization', 'joining_date',
                'salary', 'address', 'dob', 'gender', 'emergency_contact'
            ]));
        });

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->update(['is_active' => false]);
        $teacher->user->update(['is_active' => false]);
        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deactivated successfully.');
    }
}
