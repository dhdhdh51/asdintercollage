<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{SchoolClass, Section, Subject};
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount(['students', 'sections', 'subjects'])
            ->orderBy('numeric_value')->paginate(20);
        return view('admin.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:50',
            'numeric_value' => 'required|integer|min:1|max:12|unique:classes,numeric_value',
            'description'   => 'nullable|string',
        ]);
        SchoolClass::create($request->only('name', 'numeric_value', 'description'));
        return redirect()->back()->with('success', 'Class created successfully.');
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name'        => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);
        $class->update($request->only('name', 'description', 'is_active'));
        return redirect()->back()->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->students()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete class with enrolled students.');
        }
        $class->delete();
        return redirect()->back()->with('success', 'Class deleted successfully.');
    }

    // Sections
    public function sections(SchoolClass $class)
    {
        $sections = $class->sections()->withCount('students')->get();
        return view('admin.classes.sections', compact('class', 'sections'));
    }

    public function storeSection(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name'     => 'required|string|max:10',
            'capacity' => 'nullable|integer|min:1',
        ]);
        $class->sections()->create([
            'name'     => strtoupper($request->name),
            'capacity' => $request->capacity ?? 40,
        ]);
        return redirect()->back()->with('success', 'Section added successfully.');
    }

    // Subjects
    public function subjects(SchoolClass $class)
    {
        $subjects = $class->subjects()->where('is_active', true)->get();
        return view('admin.classes.subjects', compact('class', 'subjects'));
    }

    public function storeSubject(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'code'       => 'required|string|max:20|unique:subjects,code',
            'max_marks'  => 'nullable|integer|min:1',
            'pass_marks' => 'nullable|integer|min:1',
        ]);
        $class->subjects()->create([
            'name'       => $request->name,
            'code'       => strtoupper($request->code),
            'max_marks'  => $request->max_marks ?? 100,
            'pass_marks' => $request->pass_marks ?? 33,
        ]);
        return redirect()->back()->with('success', 'Subject added successfully.');
    }
}
