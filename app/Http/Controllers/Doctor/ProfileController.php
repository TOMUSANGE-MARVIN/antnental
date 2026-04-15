<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user   = Auth::user();
        $doctor = $user->doctor;
        return view('doctor.profile.edit', compact('user', 'doctor'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'          => 'nullable|string|max:20',
            'password'       => 'nullable|string|min:8|confirmed',
            'specialization' => 'nullable|string|max:255',
            'qualification'  => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'available_days' => 'nullable|string|max:255',
            'bio'            => 'nullable|string|max:1000',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $user->doctor->update([
            'specialization' => $validated['specialization'] ?? null,
            'qualification'  => $validated['qualification'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'available_days' => $validated['available_days'] ?? null,
            'bio'            => $validated['bio'] ?? null,
        ]);

        return redirect()->route('doctor.profile')->with('success', 'Profile updated successfully.');
    }
}
