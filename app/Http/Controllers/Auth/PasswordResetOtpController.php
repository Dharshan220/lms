<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Notifications\SendPasswordResetOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetOtpController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_1' => ['required', 'string', 'size:1'],
            'otp_2' => ['required', 'string', 'size:1'],
            'otp_3' => ['required', 'string', 'size:1'],
            'otp_4' => ['required', 'string', 'size:1'],
            'otp_5' => ['required', 'string', 'size:1'],
            'otp_6' => ['required', 'string', 'size:1'],
        ]);

        $otp = implode('', [
            $request->otp_1,
            $request->otp_2,
            $request->otp_3,
            $request->otp_4,
            $request->otp_5,
            $request->otp_6,
        ]);

        $record = PasswordResetOtp::valid($request->email, $otp)->first();

        if (! $record) {
            return back()->withInput()->withErrors(['otp' => 'Invalid or expired verification code. Please request a new one.']);
        }

        return redirect()->route('password.reset.form', [
            'email' => $request->email,
            'otp' => $otp,
        ]);
    }

    public function resend(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => __('We can\'t find a user with that email address.')]);
        }

        PasswordResetOtp::where('email', $request->email)->delete();

        $otp = PasswordResetOtp::generateOtp();

        PasswordResetOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        $user->notify(new SendPasswordResetOtp($otp));

        return back()->with('status', 'A new verification code has been sent to your email.');
    }
}
