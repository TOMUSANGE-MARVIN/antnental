@extends('layouts.admin')

@section('title', 'Appointment Details')
@section('header', 'Appointment Details')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Patient</p>
                <p class="font-semibold text-gray-800">{{ $appointment->patient->user->name }}</p>
                <p class="text-gray-500 text-sm">{{ $appointment->patient->user->email }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Doctor</p>
                <p class="font-semibold text-gray-800">Dr. {{ $appointment->doctor->user->name }}</p>
                <p class="text-gray-500 text-sm">{{ $appointment->doctor->specialization }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Date</p>
                <p class="font-semibold text-gray-800">{{ $appointment->appointment_date->format('l, F j, Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Time</p>
                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Type</p>
                <span class="inline-block px-3 py-1 bg-purple-50 text-purple-700 rounded-lg text-sm font-medium">{{ $appointment->type_display }}</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                    {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' :
                       ($appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                       ($appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
        </div>

        @if($appointment->notes)
        <div class="mt-6 pt-6 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Patient Notes</p>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $appointment->notes }}</p>
        </div>
        @endif

        @if($appointment->doctor_notes)
        <div class="mt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Doctor Notes</p>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $appointment->doctor_notes }}</p>
        </div>
        @endif

        <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between text-sm text-gray-400">
            <span>SMS Reminder: {{ $appointment->reminder_sent ? 'Sent' : 'Not sent' }}</span>
            <span>Created: {{ $appointment->created_at->format('M j, Y') }}</span>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.appointments.index') }}" class="text-gray-500 hover:text-teal-600 text-sm transition">← Back to Appointments</a>
    </div>
</div>
@endsection
