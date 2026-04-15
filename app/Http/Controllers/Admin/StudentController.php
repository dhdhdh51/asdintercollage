<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{SchoolClass, Section, Student, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Storage};
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class', 'section'])->where('is_active', true);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"))
                ->orWhere('student_id', 'like', "%$search%");
        }

        $students = $query->paginate(20);
        $classes  = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes  = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();
        $sections = Section::where('is_active', true)->get();
        return view('admin.students.create', compact('classes', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'nullable|string|max:15',
            'password'      => 'required|min:8|confirmed',
            'class_id'      => 'required|exists:classes,id',
            'section_id'    => 'nullable|exists:sections,id',
            'father_name'   => 'required|string|max:100',
            'dob'           => 'required|date|before:today',
            'gender'        => 'required|in:male,female,other',
            'address'       => 'required|string',
            'admission_year'=> 'required|digits:4',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'student',
            ]);

            Student::create([
                'user_id'        => $user->id,
                'student_id'     => Student::generateId(),
                'class_id'       => $request->class_id,
                'section_id'     => $request->section_id,
                'father_name'    => $request->father_name,
                'mother_name'    => $request->mother_name,
                'father_phone'   => $request->father_phone,
                'mother_phone'   => $request->mother_phone,
                'father_occupation' => $request->father_occupation,
                'dob'            => $request->dob,
                'gender'         => $request->gender,
                'address'        => $request->address,
                'city'           => $request->city,
                'state'          => $request->state,
                'pincode'        => $request->pincode,
                'blood_group'    => $request->blood_group,
                'admission_year' => $request->admission_year,
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'class', 'section', 'fees.category', 'attendances']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes  = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();
        $sections = Section::where('is_active', true)->get();
        $student->load('user');
        return view('admin.students.edit', compact('student', 'classes', 'sections'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $student->user_id,
            'class_id'   => 'required|exists:classes,id',
            'father_name'=> 'required|string|max:100',
            'dob'        => 'required|date',
            'gender'     => 'required|in:male,female,other',
            'address'    => 'required|string',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            $student->update($request->only([
                'class_id', 'section_id', 'father_name', 'mother_name',
                'father_phone', 'mother_phone', 'father_occupation',
                'dob', 'gender', 'address', 'city', 'state', 'pincode',
                'blood_group', 'roll_number'
            ]));
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->update(['is_active' => false]);
        $student->user->update(['is_active' => false]);
        return redirect()->route('admin.students.index')
            ->with('success', 'Student deactivated successfully.');
    }
}
