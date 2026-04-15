<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->paginate(15);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'required|string|max:255',
            'qualification'  => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'available_days' => 'nullable|string|max:255',
            'bio'            => 'nullable|string',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'doctor',
            'phone'     => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        Doctor::create([
            'user_id'        => $user->id,
            'specialization' => $validated['specialization'],
            'qualification'  => $validated['qualification'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'available_days' => $validated['available_days'] ?? null,
            'bio'            => $validated['bio'] ?? null,
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'appointments.patient.user']);
        $appointmentsCount = $doctor->appointments()->count();
        $patientsCount = $doctor->appointments()->distinct('patient_id')->count('patient_id');
        return view('admin.doctors.show', compact('doctor', 'appointmentsCount', 'patientsCount'));
    }

    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'required|string|max:255',
            'qualification'  => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'available_days' => 'nullable|string|max:255',
            'bio'            => 'nullable|string',
            'is_active'      => 'nullable|boolean',
        ]);

        $doctor->user->update([
            'name'      => $validated['name'],
            'phone'     => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $doctor->update([
            'specialization' => $validated['specialization'],
            'qualification'  => $validated['qualification'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'available_days' => $validated['available_days'] ?? null,
            'bio'            => $validated['bio'] ?? null,
        ]);

        return redirect()->route('admin.doctors.show', $doctor)->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor removed successfully.');
    }
}
