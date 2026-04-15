@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-3xl font-bold text-gray-800">{{ $totalPatients }}</span>
        </div>
        <p class="text-gray-500 font-medium">Total Patients</p>
        <a href="{{ route('admin.patients.index') }}" class="text-teal-600 text-sm hover:underline mt-1 inline-block">View all →</a>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <span class="text-3xl font-bold text-gray-800">{{ $totalDoctors }}</span>
        </div>
        <p class="text-gray-500 font-medium">Total Doctors</p>
        <a href="{{ route('admin.doctors.index') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Manage →</a>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-3xl font-bold text-gray-800">{{ $appointmentsToday }}</span>
        </div>
        <p class="text-gray-500 font-medium">Appointments Today</p>
        <a href="{{ route('admin.appointments.index') }}" class="text-yellow-600 text-sm hover:underline mt-1 inline-block">View →</a>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-3xl font-bold text-gray-800">{{ $upcomingAppointments }}</span>
        </div>
        <p class="text-gray-500 font-medium">Upcoming Appointments</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Appointment Trends Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Appointment Trends</h2>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <span>Last 7 days</span>
            </div>
        </div>
        <div class="h-64">
            <canvas id="appointmentTrendsChart"></canvas>
        </div>
    </div>

    <!-- Appointment Status Distribution -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Appointment Status</h2>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Current status</span>
            </div>
        </div>
        <div class="h-64">
            <canvas id="appointmentStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Secondary Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Doctor Workload Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Doctor Workload</h2>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Total appointments</span>
            </div>
        </div>
        <div class="h-64">
            <canvas id="doctorWorkloadChart"></canvas>
        </div>
    </div>

    <!-- Patient Demographics Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Patient Age Groups</h2>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Age distribution</span>
            </div>
        </div>
        <div class="h-64">
            <canvas id="patientDemographicsChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Upcoming Appointments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Upcoming Appointments</h2>
            <a href="{{ route('admin.appointments.index') }}" class="text-teal-600 text-sm hover:underline">See all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($upcomingList as $appt)
            <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-semibold text-sm">
                        {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $appt->patient->user->name }}</p>
                        <p class="text-gray-500 text-xs">Dr. {{ $appt->doctor->user->name }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-700">{{ $appt->appointment_date->format('M j') }}</p>
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                           ($appt->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ ucfirst($appt->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                No upcoming appointments
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Recent Appointments</h2>
            <a href="{{ route('admin.appointments.index') }}" class="text-teal-600 text-sm hover:underline">See all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentAppointments as $appt)
            <a href="{{ route('admin.appointments.show', $appt) }}" class="p-4 flex items-center justify-between hover:bg-gray-50 block">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-semibold text-sm">
                        {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $appt->patient->user->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $appt->type_display }} · {{ $appt->appointment_date->format('M j, Y') }}</p>
                    </div>
                </div>
                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' :
                       ($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                       ($appt->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                    {{ ucfirst($appt->status) }}
                </span>
            </a>
            @empty
            <div class="p-8 text-center text-gray-400">No appointments yet</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="{{ route('admin.doctors.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white rounded-xl p-5 flex items-center space-x-4 transition shadow-sm">
        <svg class="w-8 h-8 text-teal-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <div>
            <p class="font-semibold text-lg">Add New Doctor</p>
            <p class="text-teal-200 text-sm">Register a doctor account</p>
        </div>
    </a>
    <a href="{{ route('admin.patients.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-5 flex items-center space-x-4 transition shadow-sm">
        <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        <div>
            <p class="font-semibold text-lg">View Patients</p>
            <p class="text-blue-200 text-sm">Browse all registered patients</p>
        </div>
    </a>
    <a href="{{ route('admin.appointments.index') }}" class="bg-pink-600 hover:bg-pink-700 text-white rounded-xl p-5 flex items-center space-x-4 transition shadow-sm">
        <svg class="w-8 h-8 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <div>
            <p class="font-semibold text-lg">All Appointments</p>
            <p class="text-pink-200 text-sm">Manage all appointments</p>
        </div>
    </a>
</div>

<!-- Chart Data Scripts -->
<script>
    window.appointmentTrendsData = @json($appointmentTrends);
    window.appointmentStatusData = @json($appointmentStatusData);
    window.doctorWorkloadData = @json($doctorWorkloadData);
    window.patientDemographicsData = @json($patientDemographicsData);
</script>
@endsection
