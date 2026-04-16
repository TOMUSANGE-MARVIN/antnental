@extends('layouts.patient')

@section('title', 'My Dashboard')
@section('header', 'My Pregnancy Dashboard')

@section('content')
@php
    $pregnancyProgress = $patient->weeks_pregnant !== null ? min(100, round(($patient->weeks_pregnant / 40) * 100)) : 0;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 mb-3">Upcoming Appointments</p>
        <div class="flex items-center justify-between">
            <p class="text-3xl font-bold text-teal-700">{{ $upcomingAppointments->count() }}</p>
            <div class="w-11 h-11 bg-teal-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 mb-3">Care Completion</p>
        <div class="flex items-end justify-between">
            <p class="text-3xl font-bold text-green-600">{{ $completionRate }}%</p>
            <p class="text-xs text-gray-400 mb-1">{{ $appointmentStatusData['data'][2] }} completed of {{ $totalAppointments }}</p>
        </div>
        <div class="mt-3 bg-gray-100 rounded-full h-2.5">
            <div class="bg-green-500 h-2.5 rounded-full transition-all" style="width: {{ $completionRate }}%"></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 mb-3">Profile Completion</p>
        <div class="flex items-end justify-between">
            <p class="text-3xl font-bold text-blue-600">{{ $profileCompletion }}%</p>
            <a href="{{ route('patient.profile') }}" class="text-xs text-blue-600 hover:underline mb-1">Update</a>
        </div>
        <div class="mt-3 bg-gray-100 rounded-full h-2.5">
            <div class="bg-blue-500 h-2.5 rounded-full transition-all" style="width: {{ $profileCompletion }}%"></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 mb-3">Next Milestone</p>
        @if($nextMilestone)
            <p class="text-xl font-bold text-pink-600">Week {{ $nextMilestone['week'] }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ $nextMilestone['label'] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $nextMilestone['weeksRemaining'] }} weeks remaining</p>
        @else
            <p class="text-sm text-gray-600">Add LMP date to track milestones.</p>
        @endif
    </div>
</div>

@if($patient->lmp_date)
<div class="bg-gradient-to-r from-teal-600 to-cyan-600 rounded-2xl p-6 text-white mb-6 shadow-lg">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold">Pregnancy Progress</h2>
            <p class="text-teal-100 mt-1">{{ $patient->trimester ?? 'Pregnancy tracker' }}</p>
            @if($patient->edd_date)
                <p class="text-teal-100 text-sm mt-2">Estimated due date: {{ $patient->edd_date->format('M j, Y') }}</p>
            @endif
        </div>
        <div class="bg-white/20 rounded-2xl px-4 py-3 text-center w-full sm:w-auto sm:min-w-[180px]">
            <p class="text-xs uppercase tracking-wider text-teal-100">Current Week</p>
            <p class="text-3xl font-bold">{{ $patient->weeks_pregnant ?? 0 }}</p>
            @if($patient->days_until_edd !== null)
                <p class="text-sm text-teal-100">{{ max(0, $patient->days_until_edd) }} days to due date</p>
            @endif
        </div>
    </div>
    <div class="mt-5">
        <div class="flex justify-between text-sm text-teal-100 mb-2">
            <span>Journey progress</span>
            <span>{{ $pregnancyProgress }}%</span>
        </div>
        <div class="bg-white/25 rounded-full h-3">
            <div class="bg-pink-400 h-3 rounded-full transition-all" style="width: {{ $pregnancyProgress }}%"></div>
        </div>
    </div>
</div>
@else
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
    <div>
        <p class="font-semibold text-amber-800">Complete your pregnancy profile</p>
        <p class="text-sm text-amber-700 mt-1">Add your LMP and expected due date so your dashboard can show accurate pregnancy progress.</p>
    </div>
    <a href="{{ route('patient.profile') }}" class="w-full sm:w-auto text-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap">Update Profile</a>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Visit Trend</h3>
            <span class="text-xs text-gray-400">Last 6 months</span>
        </div>
        <div class="h-64">
            <canvas id="patientVisitTrendsChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Appointment Status</h3>
            <span class="text-xs text-gray-400">All appointments</span>
        </div>
        <div class="h-64">
            <canvas id="patientAppointmentStatusChart"></canvas>
        </div>
    </div>
</div>

@if($nextAppointment)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Next Appointment</p>
            <h3 class="font-bold text-gray-800 text-lg">Dr. {{ $nextAppointment->doctor->user->name }}</h3>
            <p class="text-gray-500">{{ $nextAppointment->doctor->specialization }}</p>
            <p class="text-teal-600 font-medium mt-1">
                {{ $nextAppointment->appointment_date->format('l, F j, Y') }} at {{ \Carbon\Carbon::parse($nextAppointment->appointment_time)->format('g:i A') }}
            </p>
        </div>
        <div class="sm:text-right">
            <span class="inline-block px-3 py-1.5 rounded-full text-sm font-semibold {{ $nextAppointment->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ ucfirst($nextAppointment->status) }}
            </span>
            <p class="text-xs text-gray-400 mt-2">{{ $nextAppointment->type_display }}</p>
        </div>
    </div>
</div>
@else
<div class="bg-pink-50 border border-pink-100 rounded-2xl p-5 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <p class="font-semibold text-pink-700">No upcoming appointments</p>
        <p class="text-pink-500 text-sm mt-1">Book your next visit and keep your care plan on track.</p>
    </div>
    <a href="{{ route('patient.appointments.create') }}" class="w-full sm:w-auto text-center bg-pink-500 hover:bg-pink-600 text-white px-5 py-2.5 rounded-xl font-medium transition text-sm whitespace-nowrap">
        Book Now
    </a>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Upcoming Visits</h3>
            <a href="{{ route('patient.appointments.index') }}" class="text-teal-600 text-sm hover:underline">See all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($upcomingAppointments as $appt)
            <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <p class="font-medium text-gray-800 text-sm">Dr. {{ $appt->doctor->user->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $appt->appointment_date->format('M j, Y') }} · {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</p>
                </div>
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($appt->status) }}
                </span>
            </div>
            @empty
            <div class="p-6 text-center text-gray-400 text-sm">No upcoming visits yet.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Recent History</h3>
            <a href="{{ route('patient.appointments.index') }}" class="text-teal-600 text-sm hover:underline">See all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($pastAppointments as $appt)
            <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <p class="font-medium text-gray-800 text-sm">Dr. {{ $appt->doctor->user->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $appt->appointment_date->format('M j, Y') }} · {{ $appt->type_display }}</p>
                </div>
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ ucfirst($appt->status) }}
                </span>
            </div>
            @empty
            <div class="p-6 text-center text-gray-400 text-sm">No past appointments yet.</div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trendCanvas = document.getElementById('patientVisitTrendsChart');
        const statusCanvas = document.getElementById('patientAppointmentStatusChart');
        const trendData = @json($appointmentTrendData);
        const statusData = @json($appointmentStatusData);

        if (trendCanvas) {
            new Chart(trendCanvas, {
                type: 'line',
                data: {
                    labels: trendData.labels,
                    datasets: [{
                        label: 'Appointments',
                        data: trendData.data,
                        borderColor: '#14B8A6',
                        backgroundColor: 'rgba(20, 184, 166, 0.12)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#0F766E'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#6B7280' } },
                        y: { beginAtZero: true, ticks: { color: '#6B7280', precision: 0 }, grid: { color: 'rgba(107,114,128,0.12)' } }
                    }
                }
            });
        }

        if (statusCanvas) {
            new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.data,
                        backgroundColor: statusData.colors,
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 18, color: '#374151' }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const values = context.dataset.data;
                                    const total = values.reduce((sum, value) => sum + value, 0);
                                    if (total === 0) {
                                        return context.label + ': 0';
                                    }
                                    const percentage = Math.round((context.raw / total) * 100);
                                    return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
