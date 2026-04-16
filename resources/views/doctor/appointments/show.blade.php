@extends('layouts.doctor')

@section('title', 'Appointment Details')
@section('header', 'Appointment Details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Appointment Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Details Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <h2 class="font-semibold text-gray-800 text-lg">Appointment #{{ $appointment->id }}</h2>
                <span class="inline-block px-3 py-1.5 rounded-full text-sm font-semibold
                    {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-700' :
                       ($appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                       ($appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Patient</p>
                    <p class="font-semibold text-gray-800">{{ $appointment->patient->user->name }}</p>
                    <p class="text-gray-500 text-sm">{{ $appointment->patient->user->phone ?? 'No phone' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Appointment Type</p>
                    <span class="inline-block px-2.5 py-1 bg-purple-50 text-purple-700 rounded-lg text-sm font-medium">{{ $appointment->type_display }}</span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Date</p>
                    <p class="font-semibold text-gray-800">{{ $appointment->appointment_date->format('l, F j, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Time</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                </div>
            </div>

            @if($appointment->notes)
            <div class="mt-5 pt-5 border-t border-gray-100">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-2">Patient Notes</p>
                <p class="text-gray-600 text-sm leading-relaxed bg-gray-50 rounded-xl p-3">{{ $appointment->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Update Status Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Update Appointment</h3>
            <form action="{{ route('doctor.appointments.update', $appointment) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $appointment->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Doctor Notes</label>
                    <textarea name="doctor_notes" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Clinical observations, prescriptions, follow-up instructions...">{{ old('doctor_notes', $appointment->doctor_notes) }}</textarea>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl transition">
                    Save Update
                </button>
            </form>
        </div>

        <!-- Schedule Next Appointment -->
        @if($appointment->status !== 'cancelled')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Schedule Next Appointment</h3>
            <form action="{{ route('doctor.appointments.schedule-next', $appointment) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Next Appointment Date *</label>
                        <input type="date" name="appointment_date" required
                               min="{{ now()->addDay()->toDateString() }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Time *</label>
                        <input type="time" name="appointment_time" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Type *</label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="follow_up">Follow-Up</option>
                            <option value="routine_checkup">Routine Checkup</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes for Next Visit</label>
                        <textarea name="notes" rows="2"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                                  placeholder="Instructions or notes for the next visit..."></textarea>
                    </div>
                </div>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-2.5 rounded-xl transition">
                    Schedule Next Appointment
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Patient Summary -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-4">Patient Summary</h3>
            <div class="text-center mb-4">
                <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-2 text-pink-700 text-xl font-bold">
                    {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
                </div>
                <p class="font-semibold text-gray-800">{{ $appointment->patient->user->name }}</p>
                @if($appointment->patient->trimester)
                    <span class="inline-block mt-1 px-2.5 py-0.5 bg-teal-100 text-teal-700 rounded-full text-xs font-medium">
                        {{ $appointment->patient->trimester }}
                    </span>
                @endif
            </div>
            <div class="space-y-2 text-sm">
                @if($appointment->patient->weeks_pregnant !== null)
                <div class="flex justify-between">
                    <span class="text-gray-400">Weeks Pregnant</span>
                    <span class="font-medium">{{ $appointment->patient->weeks_pregnant }}w</span>
                </div>
                @endif
                @if($appointment->patient->edd_date)
                <div class="flex justify-between">
                    <span class="text-gray-400">EDD</span>
                    <span class="font-medium text-teal-600">{{ $appointment->patient->edd_date->format('M j, Y') }}</span>
                </div>
                @endif
                @if($appointment->patient->blood_group)
                <div class="flex justify-between">
                    <span class="text-gray-400">Blood Group</span>
                    <span class="font-semibold text-red-600">{{ $appointment->patient->blood_group }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-400">Gravida/Para</span>
                    <span class="font-medium">G{{ $appointment->patient->gravida }} P{{ $appointment->patient->para }}</span>
                </div>
            </div>
            @if($appointment->patient->emergency_contact)
            <div class="mt-3 pt-3 border-t border-gray-100 text-sm">
                <p class="text-gray-400 text-xs mb-1">Emergency Contact</p>
                <p class="font-medium text-gray-700">{{ $appointment->patient->emergency_contact }}</p>
                <p class="text-gray-500">{{ $appointment->patient->emergency_phone }}</p>
            </div>
            @endif
        </div>
        <a href="{{ route('doctor.patients.show', $appointment->patient) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl text-sm font-medium transition text-center block">
            View Full Patient Profile
        </a>
        <a href="{{ route('doctor.appointments.index') }}" class="w-full bg-white hover:bg-gray-50 text-gray-500 border border-gray-200 py-2.5 rounded-xl text-sm font-medium transition text-center block">
            ← Back to Appointments
        </a>
    </div>
</div>
@endsection
