<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->paginate(15);
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'phone'             => 'nullable|string|max:20',
            'password'          => 'required|string|min:8|confirmed',
            'date_of_birth'     => 'nullable|date',
            'lmp_date'          => 'nullable|date',
            'blood_group'       => 'nullable|string|max:10',
            'address'           => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:20',
            'gravida'           => 'nullable|integer|min:0',
            'para'              => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'patient',
            ]);

            // Calculate EDD from LMP
            $edd = null;
            if (!empty($validated['lmp_date'])) {
                $edd = \Carbon\Carbon::parse($validated['lmp_date'])->addDays(280)->toDateString();
            }

            // Create patient profile
            $patient = Patient::create([
                'user_id' => $user->id,
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

            DB::commit();

            return redirect()->route('admin.patients.show', $patient)
                ->with('success', 'Patient created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create patient. Please try again.'])
                ->withInput();
        }
    }

    public function show(Patient $patient)
    {
        $patient->load(['user', 'appointments.doctor.user', 'deliveryRecords.doctor.user']);
        return view('admin.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $patient->load('user');
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'date_of_birth'     => 'nullable|date',
            'lmp_date'          => 'nullable|date',
            'blood_group'       => 'nullable|string|max:10',
            'address'           => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:20',
            'gravida'           => 'nullable|integer|min:0',
            'para'              => 'nullable|integer|min:0',
        ]);

        $patient->user->update([
            'name'  => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        $edd = null;
        if (!empty($validated['lmp_date'])) {
            $edd = \Carbon\Carbon::parse($validated['lmp_date'])->addDays(280)->toDateString();
        }

        $patient->update([
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

        return redirect()->route('admin.patients.show', $patient)
            ->with('success', 'Patient profile updated successfully.');
    }
}
