<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get recent orders (last 5)
        $recentOrders = $user->transactions()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        // Get account statistics
        $totalOrders = $user->transactions()->count();
        $totalSpent = $user->transactions()->sum('total');

        return view('pages.profile', compact('user', 'recentOrders', 'totalOrders', 'totalSpent'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|file|mimes:jpeg,jpg,png|max:5048',
            'remove_profile_image' => 'nullable|boolean',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $phoneBefore = $user->phone_number; // SIMPAN SEBELUM UPDATE

        // Remove profile image
        if ($request->boolean('remove_profile_image')) {
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = null;
        }

        // Upload profile image
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }

            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        // Update data
        $user->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        // Update password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password Saat ini Salah.']);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        // ✔️ CEK PERUBAHAN NOMOR HP
        if ($phoneBefore !== $request->phone_number) {
            return back()->with('success', 'Nomor Telepon berhasil diperbarui!');
        }

        return back()->with('success', 'Profil Berhasil di Update!');
    }

}
