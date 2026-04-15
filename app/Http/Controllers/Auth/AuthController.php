<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    /**
     * Handle login submission.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,student,teacher,parent',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Verify role matches
            if ($user->role !== $request->role) {
                Auth::logout();
                return back()->withErrors(['email' => 'Invalid credentials for selected role.'])->withInput();
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is deactivated. Contact admin.'])->withInput();
            }

            $request->session()->regenerate();
            return $this->redirectByRole($user->role);
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to email for password reset.
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $otp  = $user->generateOtp();

        try {
            Mail::to($user->email)->send(new OtpMail($user, $otp));
            return redirect()->route('password.verify-otp', ['email' => $request->email])
                ->with('success', 'OTP sent to your email. Valid for 10 minutes.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP. Please try again or contact admin.');
        }
    }

    /**
     * Show OTP verification form.
     */
    public function showVerifyOtp(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    /**
     * Verify OTP and show reset form.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->isOtpValid($request->otp)) {
            return back()->with('error', 'Invalid or expired OTP.')->withInput();
        }

        return view('auth.reset-password', [
            'email' => $request->email,
            'otp'   => $request->otp,
        ]);
    }

    /**
     * Reset user password after OTP verification.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'otp'                   => 'required|digits:6',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->isOtpValid($request->otp)) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        $user->update([
            'password'        => Hash::make($request->password),
            'otp'             => null,
            'otp_expires_at'  => null,
        ]);

        return redirect()->route('login')->with('success', 'Password reset successfully. Please login.');
    }

    /**
     * Redirect user to their role-specific dashboard.
     */
    private function redirectByRole(string $role)
    {
        return match($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'parent'  => redirect()->route('parent.dashboard'),
            default   => redirect('/'),
        };
    }
}
