@extends('layouts.doctor')

@section('title', 'Edit Appointment')
@section('header', 'Edit Appointment')

@section('content')
<div class="max-w-2xl mx-auto">

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 mb-6">
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <!-- Patient info header -->
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
            <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-bold text-lg">
                {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $appointment->patient->user->name }}</p>
                <p class="text-sm text-gray-400">{{ $appointment->patient->user->phone ?? 'No phone' }}</p>
            </div>
            <span class="ml-auto inline-block px-3 py-1 bg-purple-50 text-purple-700 rounded-lg text-sm font-medium">
                Appointment #{{ $appointment->id }}
            </span>
        </div>

        <form action="{{ route('doctor.appointments.update', $appointment) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Appointment Date *</label>
                    <input type="date" name="appointment_date" required
                           value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Appointment Time *</label>
                    <input type="time" name="appointment_time" required
                           value="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Type *</label>
                    <select name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['first_visit' => 'First Visit', 'follow_up' => 'Follow-Up', 'routine_checkup' => 'Routine Checkup', 'emergency' => 'Emergency'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', $appointment->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status *</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $appointment->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Patient Notes</label>
                    <textarea name="notes" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Notes from patient...">{{ old('notes', $appointment->notes) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Doctor's Clinical Notes</label>
                    <textarea name="doctor_notes" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Clinical observations, prescriptions, follow-up instructions...">{{ old('doctor_notes', $appointment->doctor_notes) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-xl transition">
                    Save Changes
                </button>
                <a href="{{ route('doctor.appointments.show', $appointment) }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
