<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            Log::error('Email verification failed: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
            ]);
            return back()->withErrors(['email' => 'Could not send verification email. Please try again later.']);
        }

        return back()->with('status', 'verification-link-sent');
    }
}
