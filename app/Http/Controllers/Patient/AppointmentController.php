<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patient;
        $appointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('appointment_date')
            ->paginate(15);
        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patient = Auth::user()->patient;

        // Check if patient already has a first-visit appointment
        $hasFirstVisit = Appointment::where('patient_id', $patient->id)
            ->where('type', 'first_visit')
            ->exists();

        if ($hasFirstVisit) {
            return redirect()->route('patient.appointments.index')
                ->with('info', 'You already have a first visit appointment. Your doctor will schedule follow-up appointments after your visit.');
        }

        $doctors = Doctor::with('user')->get();
        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $patient = Auth::user()->patient;

        // Prevent duplicate first visit
        $hasFirstVisit = Appointment::where('patient_id', $patient->id)
            ->where('type', 'first_visit')
            ->exists();

        if ($hasFirstVisit) {
            return redirect()->route('patient.appointments.index')
                ->with('info', 'You already have a first visit appointment booked.');
        }

        $validated = $request->validate([
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes'            => 'nullable|string|max:1000',
        ]);

        Appointment::create([
            'patient_id'       => $patient->id,
            'doctor_id'        => $validated['doctor_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'type'             => 'first_visit',
            'status'           => 'pending',
            'notes'            => $validated['notes'] ?? null,
            'reminder_sent'    => false,
            'created_by'       => Auth::id(),
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Your first appointment has been booked! We will confirm it shortly.');
    }
}
