<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;

        $upcomingAppointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->whereDate('appointment_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        $pastAppointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->whereDate('appointment_date', '<', today())
            ->orderByDesc('appointment_date')
            ->take(5)
            ->get();

        $statusCounts = Appointment::where('patient_id', $patient->id)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalAppointments = (int) $statusCounts->sum();
        $completedAppointments = (int) ($statusCounts['completed'] ?? 0);
        $completionRate = $totalAppointments > 0
            ? (int) round(($completedAppointments / $totalAppointments) * 100)
            : 0;
        $nextAppointment = $upcomingAppointments->first();

        $profileFields = [
            $patient->date_of_birth,
            $patient->lmp_date,
            $patient->edd_date,
            $patient->blood_group,
            $patient->address,
            $patient->emergency_contact,
            $patient->emergency_phone,
        ];

        $completedProfileFields = collect($profileFields)
            ->filter(fn ($value) => filled($value))
            ->count();

        $profileCompletion = (int) round(($completedProfileFields / count($profileFields)) * 100);
        $appointmentStatusData = $this->getAppointmentStatusData($statusCounts);
        $appointmentTrendData = $this->getAppointmentTrendData($patient->id);
        $nextMilestone = $this->getNextMilestone($patient->weeks_pregnant);

        return view('patient.dashboard', compact(
            'patient',
            'upcomingAppointments',
            'pastAppointments',
            'totalAppointments',
            'nextAppointment',
            'completionRate',
            'profileCompletion',
            'appointmentStatusData',
            'appointmentTrendData',
            'nextMilestone'
        ));
    }

    private function getAppointmentStatusData(Collection $statusCounts): array
    {
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        return [
            'labels' => array_map(fn ($status) => ucfirst($status), $statuses),
            'data' => array_map(fn ($status) => (int) ($statusCounts[$status] ?? 0), $statuses),
            'colors' => ['#FBBF24', '#60A5FA', '#34D399', '#F87171'],
        ];
    }

    private function getAppointmentTrendData(int $patientId): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $labels[] = $month->format('M');
            $data[] = Appointment::where('patient_id', $patientId)
                ->whereYear('appointment_date', $month->year)
                ->whereMonth('appointment_date', $month->month)
                ->count();
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getNextMilestone(?int $weeksPregnant): ?array
    {
        if ($weeksPregnant === null) {
            return null;
        }

        $milestones = [
            12 => 'End of 1st trimester',
            20 => 'Mid-pregnancy scan window',
            28 => 'Start of 3rd trimester',
            36 => 'Final month preparation',
            40 => 'Expected delivery week',
        ];

        foreach ($milestones as $week => $label) {
            if ($week > $weeksPregnant) {
                return [
                    'week' => $week,
                    'label' => $label,
                    'weeksRemaining' => max(0, $week - $weeksPregnant),
                ];
            }
        }

        return [
            'week' => 40,
            'label' => 'Expected delivery week',
            'weeksRemaining' => 0,
        ];
    }
}
