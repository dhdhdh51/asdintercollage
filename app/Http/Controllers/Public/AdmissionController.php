<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\AdmissionSubmittedMail;
use App\Models\{Admission, SchoolClass, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Mail, Storage};

class AdmissionController extends Controller
{
    /**
     * Show the public admission form.
     */
    public function form()
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('numeric_value')->get();
        return view('public.admission.form', compact('classes'));
    }

    /**
     * Handle admission form submission.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'student_name'   => 'required|string|max:100',
            'father_name'    => 'required|string|max:100',
            'mother_name'    => 'nullable|string|max:100',
            'dob'            => 'required|date|before:today',
            'gender'         => 'required|in:male,female,other',
            'class_id'       => 'required|exists:classes,id',
            'address'        => 'required|string|max:500',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'pincode'        => 'nullable|string|max:10',
            'phone'          => 'required|string|max:15',
            'email'          => 'nullable|email|max:100',
            'previous_school'=> 'nullable|string|max:200',
            'previous_class' => 'nullable|string|max:50',
            'document'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('public/admissions/documents');
            $documentPath = Storage::url($documentPath);
        }

        $admission = Admission::create([
            'application_id'  => Admission::generateApplicationId(),
            'student_name'    => $request->student_name,
            'father_name'     => $request->father_name,
            'mother_name'     => $request->mother_name,
            'dob'             => $request->dob,
            'gender'          => $request->gender,
            'class_id'        => $request->class_id,
            'address'         => $request->address,
            'city'            => $request->city,
            'state'           => $request->state,
            'pincode'         => $request->pincode,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'previous_school' => $request->previous_school,
            'previous_class'  => $request->previous_class,
            'document_path'   => $documentPath,
            'academic_year'   => date('Y'),
        ]);

        // Send confirmation email
        if ($admission->email) {
            try {
                Mail::to($admission->email)->send(new AdmissionSubmittedMail($admission));
            } catch (\Exception $e) {
                \Log::error('Admission submission email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admission.status', ['id' => $admission->application_id])
            ->with('success', 'Application submitted successfully! Your Application ID: ' . $admission->application_id);
    }

    /**
     * Show the admission status tracker page.
     */
    public function status(Request $request)
    {
        $admission = null;
        $error     = null;

        if ($request->filled('id')) {
            $admission = Admission::with('class')
                ->where('application_id', $request->id)
                ->first();

            if (!$admission) {
                $error = 'No application found with this ID. Please check and try again.';
            }
        }

        return view('public.admission.status', compact('admission', 'error'));
    }
}
