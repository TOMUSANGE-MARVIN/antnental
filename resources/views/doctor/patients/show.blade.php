@extends('layouts.doctor')

@section('title', 'Patient Profile')
@section('header', 'Patient Profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Patient Info -->
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="text-center mb-5">
                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3 text-pink-700 text-2xl font-bold">
                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ $patient->user->name }}</h2>
                @if($patient->trimester)
                    <span class="inline-block mt-1 px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-medium">
                        {{ $patient->trimester }}
                    </span>
                @endif
            </div>
            @if($patient->weeks_pregnant !== null)
            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-500 mb-1.5">
                    <span>Pregnancy Progress</span>
                    <span>{{ $patient->weeks_pregnant }}/40 weeks</span>
                </div>
                <div class="bg-gray-100 rounded-full h-2.5">
                    <div class="bg-teal-500 h-2.5 rounded-full" style="width: {{ min(100, ($patient->weeks_pregnant / 40) * 100) }}%"></div>
                </div>
            </div>
            @endif
            <div class="space-y-2.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Phone</span>
                    <span class="text-gray-700">{{ $patient->user->phone ?? '—' }}</span>
                </div>
                @if($patient->date_of_birth)
                <div class="flex justify-between">
                    <span class="text-gray-400">Date of Birth</span>
                    <span class="text-gray-700">{{ $patient->date_of_birth->format('M j, Y') }}</span>
                </div>
                @endif
                @if($patient->blood_group)
                <div class="flex justify-between">
                    <span class="text-gray-400">Blood Group</span>
                    <span class="font-bold text-red-600">{{ $patient->blood_group }}</span>
                </div>
                @endif
                @if($patient->lmp_date)
                <div class="flex justify-between">
                    <span class="text-gray-400">LMP</span>
                    <span class="text-gray-700">{{ $patient->lmp_date->format('M j, Y') }}</span>
                </div>
                @endif
                @if($patient->edd_date)
                <div class="flex justify-between">
                    <span class="text-gray-400">EDD</span>
                    <span class="font-semibold text-teal-600">{{ $patient->edd_date->format('M j, Y') }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-400">Gravida / Para</span>
                    <span class="text-gray-700">G{{ $patient->gravida }} P{{ $patient->para }}</span>
                </div>
            </div>
        </div>
        @if($patient->emergency_contact)
        <div class="bg-red-50 border border-red-100 rounded-2xl p-4 text-sm">
            <p class="font-semibold text-red-700 mb-1">Emergency Contact</p>
            <p class="text-gray-700">{{ $patient->emergency_contact }}</p>
            <p class="text-gray-500">{{ $patient->emergency_phone }}</p>
        </div>
        @endif
    </div>

    <!-- Appointments with this doctor -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Schedule New Appointment -->
        <div class="bg-white rounded-2xl shadow-sm border border-teal-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Schedule New Appointment
            </h3>
            <form action="{{ route('doctor.patients.schedule', $patient) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Date *</label>
                        <input type="date" name="appointment_date" required
                               min="{{ now()->toDateString() }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Time *</label>
                        <input type="time" name="appointment_time" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Type *</label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="routine_checkup">Routine Checkup</option>
                            <option value="follow_up">Follow-Up</option>
                            <option value="first_visit">First Visit</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                        <input type="text" name="notes" placeholder="Brief notes for this appointment"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-2.5 rounded-xl transition">
                    Schedule Appointment
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Appointments with You ({{ $patient->appointments->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($patient->appointments as $appt)
                <a href="{{ route('doctor.appointments.show', $appt) }}" class="p-4 flex items-center justify-between hover:bg-gray-50 block">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $appt->appointment_date->format('l, F j, Y') }}</p>
                        <p class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }} · {{ $appt->type_display }}</p>
                        @if($appt->doctor_notes)
                            <p class="text-gray-500 text-xs mt-1 truncate max-w-md">{{ $appt->doctor_notes }}</p>
                        @endif
                    </div>
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium ml-4
                        {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' :
                           ($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                           ($appt->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                        {{ ucfirst($appt->status) }}
                    </span>
                </a>
                @empty
                <div class="p-8 text-center text-gray-400">No appointments yet.</div>
                @endforelse
            </div>
        </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('doctor.patients.index') }}" class="text-gray-500 hover:text-blue-600 text-sm transition">← Back to Patients</a>
</div>
@endsection
