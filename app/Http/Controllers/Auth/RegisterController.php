<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8|confirmed',
            'phone'             => 'required|string|max:20',
            'date_of_birth'     => 'required|date|before:today',
            'lmp_date'          => 'required|date|before_or_equal:today',
            'blood_group'       => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address'           => 'nullable|string|max:500',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone'   => 'required|string|max:20',
            'gravida'           => 'nullable|integer|min:1',
            'para'              => 'nullable|integer|min:0',
        ]);

        $eddDate = Carbon::parse($validated['lmp_date'])->addDays(280);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'patient',
            'phone'    => $validated['phone'],
            'is_active' => true,
        ]);

        Patient::create([
            'user_id'           => $user->id,
            'date_of_birth'     => $validated['date_of_birth'],
            'lmp_date'          => $validated['lmp_date'],
            'edd_date'          => $eddDate->toDateString(),
            'blood_group'       => $validated['blood_group'] ?? null,
            'address'           => $validated['address'] ?? null,
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_phone'   => $validated['emergency_phone'],
            'gravida'           => $validated['gravida'] ?? 1,
            'para'              => $validated['para'] ?? 0,
        ]);

        Auth::login($user);

        return redirect()->route('patient.dashboard')->with('success', 'Welcome to MamaCare! Your account has been created successfully.');
    }
}
