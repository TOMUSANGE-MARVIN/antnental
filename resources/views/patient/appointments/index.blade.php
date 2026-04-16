@extends('layouts.patient')

@section('title', 'My Appointments')
@section('header', 'My Appointments')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
    <p class="text-gray-500">{{ $appointments->total() }} total appointments</p>
    @php
        $hasFirstVisit = $appointments->where('type', 'first_visit')->count() > 0;
    @endphp
    @if(!$hasFirstVisit)
    <a href="{{ route('patient.appointments.create') }}" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl font-medium transition flex items-center justify-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Book Appointment</span>
    </a>
    @endif
</div>

<div class="space-y-4">
    @forelse($appointments as $appt)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-4 min-w-0">
                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center text-teal-700 font-bold text-lg">
                    {{ strtoupper(substr($appt->doctor->user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-800 truncate">Dr. {{ $appt->doctor->user->name }}</p>
                    <p class="text-gray-500 text-sm">{{ $appt->doctor->specialization }}</p>
                </div>
            </div>
            <div class="sm:text-right">
                <span class="inline-block px-3 py-1.5 rounded-full text-sm font-semibold
                    {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' :
                       ($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                       ($appt->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                    {{ ucfirst($appt->status) }}
                </span>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs">Date</p>
                <p class="font-medium text-gray-700">{{ $appt->appointment_date->format('M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs">Time</p>
                <p class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs">Type</p>
                <p class="font-medium text-gray-700">{{ $appt->type_display }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs">SMS Reminder</p>
                <p class="font-medium {{ $appt->reminder_sent ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $appt->reminder_sent ? 'Sent' : 'Pending' }}
                </p>
            </div>
        </div>
        @if($appt->notes)
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-gray-500 text-sm">{{ $appt->notes }}</p>
        </div>
        @endif
        @if($appt->doctor_notes)
        <div class="mt-2 bg-blue-50 rounded-xl px-4 py-2.5 text-sm">
            <p class="text-xs text-blue-400 font-semibold uppercase tracking-wider mb-1">Doctor's Notes</p>
            <p class="text-blue-700">{{ $appt->doctor_notes }}</p>
        </div>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-gray-400 font-medium text-lg mb-2">No appointments yet</p>
        <p class="text-gray-400 text-sm mb-5">Book your first appointment to begin your antenatal care.</p>
        <a href="{{ route('patient.appointments.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-xl font-medium transition inline-block">
            Book First Appointment
        </a>
    </div>
    @endforelse
</div>
@if($appointments->hasPages())
<div class="mt-6">{{ $appointments->links() }}</div>
@endif
@endsection
