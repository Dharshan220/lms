<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        $redirectUri = config('services.google.redirect');
        if (!$redirectUri) {
            $redirectUri = url('auth/google/callback');
        }
        return Socialite::driver('google')
            ->redirectUrl($redirectUri)
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $redirectUri = config('services.google.redirect');
            if (!$redirectUri) {
                $redirectUri = url('auth/google/callback');
            }
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['google' => 'Google login failed. Please try again.']);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_avatar' => $googleUser->getAvatar(),
                    'google_token' => $googleUser->token,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'google_avatar' => $googleUser->getAvatar(),
                    'google_token' => $googleUser->token,
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(),
                ]);
            }
        }

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
