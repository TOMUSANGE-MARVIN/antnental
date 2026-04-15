<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        $appointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->orderByDesc('appointment_date')
            ->paginate(15);
        return view('doctor.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        $appointment->load(['patient.user', 'doctor.user', 'creator']);
        return view('doctor.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        $appointment->load(['patient.user']);
        return view('doctor.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'type'             => 'required|in:first_visit,follow_up,routine_checkup,emergency',
            'status'           => 'required|in:pending,confirmed,completed,cancelled',
            'notes'            => 'nullable|string',
            'doctor_notes'     => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('doctor.appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function scheduleNext(Request $request, Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        $validated = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'type'             => 'required|in:follow_up,routine_checkup',
            'notes'            => 'nullable|string',
        ]);

        $next = Appointment::create([
            'patient_id'       => $appointment->patient_id,
            'doctor_id'        => $appointment->doctor_id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'type'             => $validated['type'],
            'status'           => 'confirmed',
            'notes'            => $validated['notes'] ?? null,
            'reminder_sent'    => false,
            'created_by'       => Auth::id(),
        ]);

        // Mark current as completed if not already
        if ($appointment->status !== 'completed') {
            $appointment->update(['status' => 'completed']);
        }

        return redirect()->route('doctor.appointments.show', $next)
            ->with('success', 'Next appointment scheduled successfully.');
    }

    private function authorizeDoctor(Appointment $appointment): void
    {
        $doctor = Auth::user()->doctor;
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403, 'You are not authorized to view this appointment.');
        }
    }
}
