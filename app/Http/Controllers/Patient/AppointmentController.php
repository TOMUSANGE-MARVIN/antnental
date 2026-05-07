<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\SmsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        $doctors = Doctor::with('user')->get();
        $visitReasons = Appointment::BOOKING_REASONS;
        $reasonDoctorIds = $this->getReasonDoctorIds($doctors);

        return view('patient.appointments.create', compact('doctors', 'visitReasons', 'reasonDoctorIds'));
    }

    public function store(Request $request)
    {
        $patient = Auth::user()->patient;
        $validated = $this->validateAppointmentRequest($request);
        $doctorId = $this->resolveDoctorId($validated);

        $appointmentType = Appointment::where('patient_id', $patient->id)->exists()
            ? 'follow_up'
            : 'first_visit';

        Appointment::create([
            'patient_id'       => $patient->id,
            'doctor_id'        => $doctorId,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'type'             => $appointmentType,
            'visit_reason'     => $validated['visit_reason'],
            'other_reason'     => $validated['visit_reason'] === 'other' ? $validated['other_reason'] : null,
            'status'           => 'pending',
            'notes'            => $validated['notes'] ?? null,
            'reminder_sent'    => false,
            'created_by'       => Auth::id(),
        ]);

        $message = $validated['visit_reason'] === 'other'
            ? 'Your appointment request has been received. Admin will assign the right doctor shortly.'
            : 'Your appointment has been booked! We will confirm it shortly.';

        return redirect()->route('patient.appointments.index')
            ->with('success', $message);
    }

    public function edit(Appointment $appointment)
    {
        $patient = Auth::user()->patient;
        $this->ensureOwnAppointment($appointment, $patient->id);

        if (!$this->canEditAppointment($appointment)) {
            return redirect()->route('patient.appointments.index')
                ->with('info', 'Only upcoming pending/confirmed appointments can be edited.');
        }

        $doctors = Doctor::with('user')->get();
        $visitReasons = Appointment::BOOKING_REASONS;
        $reasonDoctorIds = $this->getReasonDoctorIds($doctors);

        return view('patient.appointments.edit', compact('appointment', 'doctors', 'visitReasons', 'reasonDoctorIds'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $patient = Auth::user()->patient;
        $this->ensureOwnAppointment($appointment, $patient->id);

        if (!$this->canEditAppointment($appointment)) {
            return redirect()->route('patient.appointments.index')
                ->with('info', 'Only upcoming pending/confirmed appointments can be edited.');
        }

        $validated = $this->validateAppointmentRequest($request);
        $doctorId = $this->resolveDoctorId($validated);

        $appointment->update([
            'doctor_id'        => $doctorId,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'visit_reason'     => $validated['visit_reason'],
            'other_reason'     => $validated['visit_reason'] === 'other' ? $validated['other_reason'] : null,
            'notes'            => $validated['notes'] ?? null,
        ]);

        if ($appointment->wasChanged(['appointment_date', 'appointment_time', 'doctor_id', 'visit_reason', 'other_reason'])) {
            SmsNotification::query()
                ->where('notifiable_type', Appointment::class)
                ->where('notifiable_id', $appointment->id)
                ->where('status', 'pending')
                ->delete();

            $appointment->forceFill(['reminder_sent' => false])->saveQuietly();
            $appointment->scheduleReminderNotification();
        }

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    private function validateAppointmentRequest(Request $request): array
    {
        $reasonKeys = implode(',', array_keys(Appointment::BOOKING_REASONS));

        return $request->validate([
            'visit_reason'     => "required|in:{$reasonKeys}",
            'doctor_id'        => 'nullable|required_unless:visit_reason,other|exists:doctors,id',
            'other_reason'     => 'nullable|required_if:visit_reason,other|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes'            => 'nullable|string|max:1000',
        ]);
    }

    private function resolveDoctorId(array $validated): ?int
    {
        if ($validated['visit_reason'] === 'other') {
            return null;
        }

        $doctorId = (int) $validated['doctor_id'];
        $reasonDoctorIds = $this->getReasonDoctorIds(
            Doctor::query()->get(['id', 'specialization'])
        );

        if (!in_array($doctorId, $reasonDoctorIds[$validated['visit_reason']] ?? [], true)) {
            throw ValidationException::withMessages([
                'doctor_id' => 'Please select a doctor that matches the selected visit reason.',
            ]);
        }

        return $doctorId;
    }

    private function ensureOwnAppointment(Appointment $appointment, int $patientId): void
    {
        abort_unless($appointment->patient_id === $patientId, 403);
    }

    private function canEditAppointment(Appointment $appointment): bool
    {
        return in_array($appointment->status, ['pending', 'confirmed'], true)
            && !$appointment->appointment_date->isPast();
    }

    private function getReasonDoctorIds($doctors): array
    {
        $allDoctorIds = $doctors->pluck('id')->values()->all();
        $reasonDoctorIds = [];

        foreach (Appointment::REASON_SPECIALIZATION_KEYWORDS as $reason => $keywords) {
            $matchedDoctorIds = $doctors
                ->filter(function (Doctor $doctor) use ($keywords): bool {
                    $specialization = strtolower((string) $doctor->specialization);

                    foreach ($keywords as $keyword) {
                        if (str_contains($specialization, strtolower($keyword))) {
                            return true;
                        }
                    }

                    return false;
                })
                ->pluck('id')
                ->values()
                ->all();

            $reasonDoctorIds[$reason] = empty($matchedDoctorIds) ? $allDoctorIds : $matchedDoctorIds;
        }

        $reasonDoctorIds['other'] = [];

        return $reasonDoctorIds;
    }
}
