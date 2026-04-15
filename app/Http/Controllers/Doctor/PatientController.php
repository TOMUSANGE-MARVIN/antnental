<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        $patientIds = Appointment::where('doctor_id', $doctor->id)
            ->distinct()
            ->pluck('patient_id');

        $patients = Patient::with('user')
            ->whereIn('id', $patientIds)
            ->paginate(15);

        return view('doctor.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $doctor = Auth::user()->doctor;

        // Ensure this doctor has treated this patient
        $hasAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->exists();

        if (!$hasAppointment) {
            abort(403, 'You do not have access to this patient.');
        }

        $patient->load(['user', 'appointments' => function ($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id)->orderByDesc('appointment_date');
        }]);

        return view('doctor.patients.show', compact('patient', 'doctor'));
    }

    public function scheduleAppointment(Request $request, Patient $patient)
    {
        $doctor = Auth::user()->doctor;

        $validated = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'type'             => 'required|in:first_visit,follow_up,routine_checkup,emergency',
            'notes'            => 'nullable|string|max:1000',
        ]);

        Appointment::create([
            'patient_id'       => $patient->id,
            'doctor_id'        => $doctor->id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'type'             => $validated['type'],
            'status'           => 'confirmed',
            'notes'            => $validated['notes'] ?? null,
            'reminder_sent'    => false,
            'created_by'       => Auth::id(),
        ]);

        return redirect()->route('doctor.patients.show', $patient)
            ->with('success', 'Appointment scheduled successfully.');
    }
}
