<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /** Redirect to Google OAuth page */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /** Handle callback from Google */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal autentikasi dengan Google: ' . $e->getMessage());
        }

        // find or create user
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // attach google_id if missing
            if (empty($user->google_id)) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(uniqid('google_', true)),
                'role' => 'user',
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);
        return redirect()->intended(route('user.dashboard'));
    }
}
