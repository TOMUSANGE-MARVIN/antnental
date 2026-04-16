@extends('layouts.admin')

@section('title', 'Doctor Profile')
@section('header', 'Doctor Profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Doctor Info Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3 text-teal-700 text-3xl font-bold">
                {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
            </div>
            <h2 class="text-xl font-bold text-gray-800">Dr. {{ $doctor->user->name }}</h2>
            <p class="text-teal-600 font-medium">{{ $doctor->specialization }}</p>
            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium {{ $doctor->user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $doctor->user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex items-center text-gray-600">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $doctor->user->email }}
            </div>
            @if($doctor->user->phone)
            <div class="flex items-center text-gray-600">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                {{ $doctor->user->phone }}
            </div>
            @endif
            @if($doctor->qualification)
            <div class="flex items-center text-gray-600">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                {{ $doctor->qualification }}
            </div>
            @endif
            @if($doctor->license_number)
            <div class="flex items-center text-gray-600">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                License: {{ $doctor->license_number }}
            </div>
            @endif
            @if($doctor->available_days)
            <div class="flex items-center text-gray-600">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $doctor->available_days }}
            </div>
            @endif
        </div>
        @if($doctor->bio)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-gray-500 text-sm leading-relaxed">{{ $doctor->bio }}</p>
        </div>
        @endif
        <div class="mt-6 space-y-2">
            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-xl text-sm font-medium transition text-center block">
                Edit Profile
            </a>
            <a href="{{ route('admin.doctors.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-xl text-sm font-medium transition text-center block">
                Back to Doctors
            </a>
        </div>
    </div>

    <!-- Stats & Appointments -->
    <div class="lg:col-span-2 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                <p class="text-3xl font-bold text-teal-600">{{ $patientsCount }}</p>
                <p class="text-gray-500 mt-1">Total Patients</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                <p class="text-3xl font-bold text-blue-600">{{ $appointmentsCount }}</p>
                <p class="text-gray-500 mt-1">Total Appointments</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Recent Appointments</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($doctor->appointments->take(8) as $appt)
                <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-gray-50">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-semibold text-sm">
                            {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-gray-800 text-sm truncate">{{ $appt->patient->user->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $appt->type_display }}</p>
                        </div>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-sm text-gray-600">{{ $appt->appointment_date->format('M j, Y') }}</p>
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' :
                               ($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                               ($appt->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                            {{ ucfirst($appt->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">No appointments yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
