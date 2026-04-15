<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatients     = Patient::count();
        $totalDoctors      = Doctor::count();
        $appointmentsToday = Appointment::whereDate('appointment_date', today())->count();
        $upcomingAppointments = Appointment::whereDate('appointment_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $upcomingList = Appointment::with(['patient.user', 'doctor.user'])
            ->whereDate('appointment_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(10)
            ->get();

        // Chart data for appointment trends (last 7 days)
        $appointmentTrends = $this->getAppointmentTrendsData();
        
        // Chart data for appointment status distribution
        $appointmentStatusData = $this->getAppointmentStatusData();
        
        // Chart data for doctor workload
        $doctorWorkloadData = $this->getDoctorWorkloadData();
        
        // Chart data for patient demographics (age groups)
        $patientDemographicsData = $this->getPatientDemographicsData();

        return view('admin.dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'appointmentsToday',
            'upcomingAppointments',
            'recentAppointments',
            'upcomingList',
            'appointmentTrends',
            'appointmentStatusData',
            'doctorWorkloadData',
            'patientDemographicsData'
        ));
    }

    private function getAppointmentTrendsData()
    {
        $trends = [];
        $labels = [];
        
        // Get data for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M j');
            $count = Appointment::whereDate('appointment_date', $date)->count();
            $trends[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $trends
        ];
    }

    private function getAppointmentStatusData()
    {
        $statusCounts = Appointment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'labels' => array_keys($statusCounts),
            'data' => array_values($statusCounts),
            'colors' => [
                'pending' => '#FCD34D',
                'confirmed' => '#60A5FA', 
                'completed' => '#34D399',
                'cancelled' => '#F87171'
            ]
        ];
    }

    private function getDoctorWorkloadData()
    {
        $workload = Doctor::with('user')
            ->select('doctors.*')
            ->leftJoin('appointments', 'doctors.id', '=', 'appointments.doctor_id')
            ->selectRaw('doctors.*, COUNT(appointments.id) as appointment_count')
            ->groupBy('doctors.id', 'doctors.user_id', 'doctors.specialization', 'doctors.license_number', 'doctors.created_at', 'doctors.updated_at')
            ->orderBy('appointment_count', 'desc')
            ->limit(10)
            ->get();

        return [
            'labels' => $workload->map(fn($doctor) => 'Dr. ' . $doctor->user->name)->toArray(),
            'data' => $workload->map(fn($doctor) => $doctor->appointment_count)->toArray()
        ];
    }

    private function getPatientDemographicsData()
    {
        // Get age groups distribution - SQLite compatible version
        $ageGroups = Patient::select(
            DB::raw('CASE 
                WHEN (julianday("now") - julianday(date_of_birth)) / 365.25 < 20 THEN "Under 20"
                WHEN (julianday("now") - julianday(date_of_birth)) / 365.25 BETWEEN 20 AND 25 THEN "20-25"
                WHEN (julianday("now") - julianday(date_of_birth)) / 365.25 BETWEEN 26 AND 30 THEN "26-30"
                WHEN (julianday("now") - julianday(date_of_birth)) / 365.25 BETWEEN 31 AND 35 THEN "31-35"
                WHEN (julianday("now") - julianday(date_of_birth)) / 365.25 BETWEEN 36 AND 40 THEN "36-40"
                ELSE "Over 40"
                END as age_group'),
            DB::raw('COUNT(*) as count')
        )
        ->whereNotNull('date_of_birth')
        ->groupBy('age_group')
        ->orderBy('age_group')
        ->get();

        return [
            'labels' => $ageGroups->pluck('age_group')->toArray(),
            'data' => $ageGroups->pluck('count')->toArray()
        ];
    }
}
