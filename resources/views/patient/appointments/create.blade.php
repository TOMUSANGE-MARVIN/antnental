@extends('layouts.patient')

@section('title', 'Book Appointment')
@section('header', 'Book Your First Appointment')

@section('content')
<div class="max-w-xl">
    <div class="bg-teal-50 border border-teal-200 rounded-2xl p-4 mb-6 flex items-start space-x-3">
        <svg class="w-5 h-5 text-teal-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-sm text-teal-700">
            <p class="font-semibold">First Visit Appointment</p>
            <p class="mt-0.5 text-teal-600">Book your first antenatal visit. Your doctor will schedule follow-up appointments after your initial consultation.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('patient.appointments.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Doctor *</label>
                <select name="doctor_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('doctor_id') border-red-400 @enderror">
                    <option value="">Choose your doctor...</option>
                    @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        Dr. {{ $doctor->user->name }} — {{ $doctor->specialization }}
                        @if($doctor->available_days) ({{ $doctor->available_days }}) @endif
                    </option>
                    @endforeach
                </select>
                @error('doctor_id') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                @if($doctors->isEmpty())
                    <p class="mt-2 text-yellow-600 text-sm">No doctors available. Please contact admin.</p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Appointment Date *</label>
                    <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" required
                           min="{{ today()->toDateString() }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('appointment_date') border-red-400 @enderror">
                    @error('appointment_date') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Preferred Time *</label>
                    <input type="time" name="appointment_time" value="{{ old('appointment_time') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('appointment_time') border-red-400 @enderror">
                    @error('appointment_time') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Additional Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                          placeholder="Any concerns, symptoms, or notes for your doctor...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center space-x-4">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-8 py-2.5 rounded-xl transition shadow-sm">
                    Book Appointment
                </button>
                <a href="{{ route('patient.appointments.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Cancel</a>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-gray-50 rounded-2xl p-5 text-sm text-gray-500">
        <h4 class="font-semibold text-gray-700 mb-2">What to expect:</h4>
        <ul class="space-y-1.5">
            <li class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-teal-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Your appointment request will be reviewed and confirmed</span>
            </li>
            <li class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-teal-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>You'll receive an SMS reminder 24 hours before your appointment</span>
            </li>
            <li class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-teal-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>After your first visit, your doctor will schedule follow-up appointments</span>
            </li>
        </ul>
    </div>
</div>
@endsection
