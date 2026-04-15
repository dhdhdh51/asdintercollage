<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdmissionStatusMail;
use App\Models\{Admission, SchoolClass};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Admission::with('class')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%$search%")
                  ->orWhere('application_id', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $admissions = $query->paginate(20);
        $classes    = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();
        $counts     = [
            'all'      => Admission::count(),
            'pending'  => Admission::where('status', 'pending')->count(),
            'approved' => Admission::where('status', 'approved')->count(),
            'rejected' => Admission::where('status', 'rejected')->count(),
        ];

        return view('admin.admissions.index', compact('admissions', 'classes', 'counts'));
    }

    public function show(Admission $admission)
    {
        $admission->load(['class', 'reviewer']);
        return view('admin.admissions.show', compact('admission'));
    }

    public function approve(Request $request, Admission $admission)
    {
        $request->validate(['remarks' => 'nullable|string|max:500']);

        $admission->update([
            'status'      => 'approved',
            'remarks'     => $request->remarks,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Send email notification
        if ($admission->email) {
            try {
                Mail::to($admission->email)->send(new AdmissionStatusMail($admission));
            } catch (\Exception $e) {
                // Log email failure but don't break the approval
                \Log::error('Admission approval email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.admissions.show', $admission)
            ->with('success', 'Admission approved and applicant notified.');
    }

    public function reject(Request $request, Admission $admission)
    {
        $request->validate(['remarks' => 'required|string|max:500']);

        $admission->update([
            'status'      => 'rejected',
            'remarks'     => $request->remarks,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        if ($admission->email) {
            try {
                Mail::to($admission->email)->send(new AdmissionStatusMail($admission));
            } catch (\Exception $e) {
                \Log::error('Admission rejection email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.admissions.show', $admission)
            ->with('success', 'Admission rejected and applicant notified.');
    }
}
