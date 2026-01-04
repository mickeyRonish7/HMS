<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // Registration
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:student,visitor'],
            // Student specific fields
            'parent_phone' => ['required_if:role,student', 'nullable', 'string', 'max:20'],
            'year' => ['required_if:role,student', 'nullable', 'integer', 'between:1,4'],
            'department' => ['required_if:role,student', 'nullable', 'string', 'max:100'],
            'semester' => ['required_if:role,student', 'nullable', 'integer', 'between:1,8'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'parent_phone' => $request->role === 'student' ? $request->parent_phone : null,
            'year' => $request->role === 'student' ? $request->year : null,
            'department' => $request->role === 'student' ? $request->department : null,
            'semester' => $request->role === 'student' ? $request->semester : null,
            'address' => $request->address,
        ];

        if ($request->role === 'visitor') {
            // Visitors need dual approval
            $userData['student_approved'] = false;
            $userData['admin_approved'] = false;
            $userData['is_approved'] = false;
        } else {
            // Students use traditional approval
            $userData['is_approved'] = false;
        }

        $user = User::create($userData);

        if ($user->role === 'visitor') {
            app(\App\Services\NotificationService::class)->notifyNewVisitorRegistration($user->id);
        }

        // Auto-login or redirect with message?
        // User requested: "register requste go to database and admin to aprove ... then student login"
        // So DO NOT auto-login.

        return redirect()->route('login')->with('success', 'Registration successful! Please wait for Admin approval before logging in.');
    }

    // Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check approval mechanism
            if ($user->role === 'visitor') {
                // Visitors need BOTH student and admin approval
                if (!$user->student_approved || !$user->admin_approved) {
                    Auth::logout();
                    $missing = [];
                    if (!$user->student_approved) $missing[] = 'student';
                    if (!$user->admin_approved) $missing[] = 'admin';
                    return back()->withErrors([
                        'email' => 'Your account is pending approval from: ' . implode(' and ', $missing) . '.',
                    ]);
                }
            } elseif ($user->role === 'student' && !$user->is_approved) {
                // Students need admin approval
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval by the Administrator.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Password Reset (Simple Logic for Demo)
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => "We can't find a user with that email address."]);
        }

        // Generate Token
        $token = \Illuminate\Support\Facades\Password::createToken($user);
        
        // Construct Link
        $link = route('password.reset', ['token' => $token, 'email' => $request->email]);
        
        // For DEMO purposes: display the link in the status message
        return back()->with('status', "Demo Mode: Click this link to reset password: <a href=\"{$link}\" class=\"font-bold underline\">Reset Password</a>");
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(\Illuminate\Support\Str::random(60));

                $user->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        return $status == \Illuminate\Support\Facades\Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
