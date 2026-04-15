<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;

        $todayAppointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $upcomingAppointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', '>', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(10)
            ->get();

        $totalPatients = $doctor->appointments()
            ->distinct('patient_id')
            ->count('patient_id');

        $totalAppointments = $doctor->appointments()->count();

        $recentPatients = $doctor->appointments()
            ->with('patient.user')
            ->distinct('patient_id')
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->pluck('patient')
            ->unique('id');

        return view('doctor.dashboard', compact(
            'todayAppointments',
            'upcomingAppointments',
            'totalPatients',
            'totalAppointments',
            'recentPatients'
        ));
    }
}
