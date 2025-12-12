<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(rand(100000, 999999)),
                    'role' => 'user' // Default role for Google login users
                ]);
            }

            Auth::login($user);

            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Google. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // keluar dari session user

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // arahkan ke halaman utama
    }
}
