<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $patient = $user->patient;
        return view('patient.profile.edit', compact('user', 'patient'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'             => 'nullable|string|max:20',
            'password'          => 'nullable|string|min:8|confirmed',
            'date_of_birth'     => 'nullable|date',
            'lmp_date'          => 'nullable|date',
            'blood_group'       => 'nullable|string|max:10',
            'address'           => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:20',
            'gravida'           => 'nullable|integer|min:0',
            'para'              => 'nullable|integer|min:0',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $edd = null;
        if (!empty($validated['lmp_date'])) {
            $edd = \Carbon\Carbon::parse($validated['lmp_date'])->addDays(280)->toDateString();
        }

        $user->patient->update([
            'date_of_birth'     => $validated['date_of_birth'] ?? null,
            'lmp_date'          => $validated['lmp_date'] ?? null,
            'edd_date'          => $edd,
            'blood_group'       => $validated['blood_group'] ?? null,
            'address'           => $validated['address'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'emergency_phone'   => $validated['emergency_phone'] ?? null,
            'gravida'           => $validated['gravida'] ?? 1,
            'para'              => $validated['para'] ?? 0,
        ]);

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully.');
    }
}
