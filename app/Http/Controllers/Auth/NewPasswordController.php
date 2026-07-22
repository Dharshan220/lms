<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.new-password', [
            'email' => $request->email,
            'otp' => $request->otp,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $record = PasswordResetOtp::valid($request->email, $request->otp)->first();

        if (! $record) {
            return back()->withInput($request->only('email'))
                ->withErrors(['otp' => 'Invalid or expired verification code. Please start the password reset process again.']);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('We can\'t find a user with that email address.')]);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        $user->tokens()->delete();

        event(new PasswordReset($user));

        PasswordResetOtp::where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset successfully. You can now log in with your new password.');
    }
}
