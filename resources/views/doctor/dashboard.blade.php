@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')
@section('header', 'My Dashboard')

@section('content')
<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-3xl font-bold text-gray-800">{{ $totalPatients }}</span>
        </div>
        <p class="text-gray-500 font-medium">My Patients</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-3xl font-bold text-gray-800">{{ $todayAppointments->count() }}</span>
        </div>
        <p class="text-gray-500 font-medium">Today's Appointments</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <span class="text-3xl font-bold text-gray-800">{{ $totalAppointments }}</span>
        </div>
        <p class="text-gray-500 font-medium">Total Appointments</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Today's Appointments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Today's Schedule</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($todayAppointments as $appt)
            <a href="{{ route('doctor.appointments.show', $appt) }}" class="p-4 flex items-center justify-between hover:bg-gray-50 block">
                <div class="flex items-center space-x-3">
                    <div class="text-center w-12">
                        <p class="text-sm font-bold text-blue-600">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i') }}</p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('A') }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $appt->patient->user->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $appt->type_display }}</p>
                    </div>
                </div>
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium
                    {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' :
                       ($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($appt->status) }}
                </span>
            </a>
            @empty
            <div class="p-8 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                No appointments today
            </div>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Upcoming</h2>
            <a href="{{ route('doctor.appointments.index') }}" class="text-blue-600 text-sm hover:underline">See all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($upcomingAppointments as $appt)
            <a href="{{ route('doctor.appointments.show', $appt) }}" class="p-4 flex items-center justify-between hover:bg-gray-50 block">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-semibold text-sm">
                        {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $appt->patient->user->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $appt->appointment_date->format('M j') }} · {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</p>
                    </div>
                </div>
                <span class="text-xs text-gray-400">{{ $appt->type_display }}</span>
            </a>
            @empty
            <div class="p-8 text-center text-gray-400">No upcoming appointments</div>
            @endforelse
        </div>
    </div>
</div>

@if($recentPatients->count() > 0)
<div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Recent Patients</h2>
        <a href="{{ route('doctor.patients.index') }}" class="text-blue-600 text-sm hover:underline">View all</a>
    </div>
    <div class="p-5 flex flex-wrap gap-3">
        @foreach($recentPatients as $patient)
        <a href="{{ route('doctor.patients.show', $patient) }}" class="flex items-center space-x-3 bg-gray-50 hover:bg-teal-50 rounded-xl px-4 py-3 transition">
            <div class="w-9 h-9 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-semibold text-sm">
                {{ strtoupper(substr($patient->user->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-medium text-gray-800 text-sm">{{ $patient->user->name }}</p>
                @if($patient->weeks_pregnant !== null)
                    <p class="text-gray-400 text-xs">{{ $patient->weeks_pregnant }} weeks pregnant</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
@endsection
