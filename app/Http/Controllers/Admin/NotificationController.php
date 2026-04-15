<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GeneralNotificationMail;
use App\Models\{Notification, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('creator')->latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'message'     => 'required|string',
            'type'        => 'required|in:info,success,warning,danger',
            'target_role' => 'nullable|in:student,teacher,parent,admin',
            'send_email'  => 'nullable|boolean',
        ]);

        $notification = Notification::create([
            'title'       => $request->title,
            'message'     => $request->message,
            'type'        => $request->type,
            'target_role' => $request->target_role,
            'send_email'  => $request->boolean('send_email'),
            'created_by'  => auth()->id(),
        ]);

        // Send emails if requested
        if ($request->boolean('send_email')) {
            $query = User::where('is_active', true);
            if ($request->target_role) {
                $query->where('role', $request->target_role);
            }

            $users = $query->get();
            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(new GeneralNotificationMail($notification, $user));
                } catch (\Exception $e) {
                    \Log::error('Notification email failed for ' . $user->email . ': ' . $e->getMessage());
                }
            }

            $notification->update(['email_sent' => true]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification sent successfully.');
    }
}
