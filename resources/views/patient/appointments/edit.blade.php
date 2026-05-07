@extends('layouts.patient')

@section('title', 'Edit Appointment')
@section('header', 'Edit Appointment')

@section('content')
<div class="max-w-xl">
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start space-x-3">
        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-sm text-amber-700">
            <p class="font-semibold">Update Appointment #{{ $appointment->id }}</p>
            <p class="mt-0.5 text-amber-600">You can edit pending/confirmed appointments before the visit date.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('patient.appointments.update', $appointment) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Visit Reason *</label>
                <select name="visit_reason" id="visit_reason" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('visit_reason') border-red-400 @enderror">
                    <option value="">Select reason...</option>
                    @foreach($visitReasons as $reasonKey => $reasonLabel)
                        <option value="{{ $reasonKey }}" {{ old('visit_reason', $appointment->visit_reason) === $reasonKey ? 'selected' : '' }}>
                            {{ $reasonLabel }}
                        </option>
                    @endforeach
                </select>
                @error('visit_reason') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div id="doctor-wrapper" class="{{ old('visit_reason', $appointment->visit_reason) === 'other' ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Doctor *</label>
                <select name="doctor_id" id="doctor_id" {{ old('visit_reason', $appointment->visit_reason) === 'other' ? '' : 'required' }}
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('doctor_id') border-red-400 @enderror">
                    <option value="">Choose your doctor...</option>
                    @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ (string) old('doctor_id', $appointment->doctor_id) === (string) $doctor->id ? 'selected' : '' }}>
                        Dr. {{ $doctor->user->name }} — {{ $doctor->specialization }}
                        @if($doctor->available_days) ({{ $doctor->available_days }}) @endif
                    </option>
                    @endforeach
                </select>
                <p id="doctor-filter-hint" class="mt-1 text-xs text-gray-500"></p>
                @error('doctor_id') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div id="other-reason-wrapper" class="{{ old('visit_reason', $appointment->visit_reason) === 'other' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Please specify *</label>
                <input type="text" name="other_reason" id="other_reason" value="{{ old('other_reason', $appointment->other_reason) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('other_reason') border-red-400 @enderror"
                       placeholder="Describe the reason for your appointment">
                <p class="mt-1 text-xs text-gray-500">For "Other", an admin will assign the most appropriate doctor.</p>
                @error('other_reason') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Appointment Date *</label>
                    <input type="date" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required
                           min="{{ today()->toDateString() }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('appointment_date') border-red-400 @enderror">
                    @error('appointment_date') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Preferred Time *</label>
                    <input type="time" name="appointment_time" value="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('appointment_time') border-red-400 @enderror">
                    @error('appointment_time') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Additional Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                          placeholder="Any concerns, symptoms, or notes for your doctor...">{{ old('notes', $appointment->notes) }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                <button type="submit" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white font-semibold px-8 py-2.5 rounded-xl transition shadow-sm">
                    Save Changes
                </button>
                <a href="{{ route('patient.appointments.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const reasonDoctorIds = @json($reasonDoctorIds);
        const reasonSelect = document.getElementById('visit_reason');
        const doctorWrapper = document.getElementById('doctor-wrapper');
        const doctorSelect = document.getElementById('doctor_id');
        const otherWrapper = document.getElementById('other-reason-wrapper');
        const otherReasonInput = document.getElementById('other_reason');
        const doctorHint = document.getElementById('doctor-filter-hint');

        if (!reasonSelect || !doctorSelect || !doctorWrapper || !otherWrapper || !otherReasonInput) {
            return;
        }

        const doctorOptions = Array.from(doctorSelect.options).slice(1);

        const setDoctorVisibility = (reason) => {
            const allowed = new Set((reasonDoctorIds[reason] || []).map(String));
            let visibleCount = 0;

            doctorOptions.forEach((option) => {
                const visible = !reason || reason === 'other' ? true : allowed.has(option.value);
                option.hidden = !visible;

                if (visible) {
                    visibleCount++;
                }
            });

            const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
            if (selectedOption && selectedOption.hidden) {
                doctorSelect.value = '';
            }

            doctorHint.textContent = reason && reason !== 'other'
                ? `${visibleCount} matching doctor${visibleCount === 1 ? '' : 's'} available for this reason.`
                : '';
        };

        const updateReasonState = () => {
            const selectedReason = reasonSelect.value;
            const isOther = selectedReason === 'other';

            doctorWrapper.classList.toggle('hidden', isOther);
            doctorSelect.required = !isOther;

            otherWrapper.classList.toggle('hidden', !isOther);
            otherReasonInput.required = isOther;

            setDoctorVisibility(selectedReason);
        };

        reasonSelect.addEventListener('change', updateReasonState);
        updateReasonState();
    });
</script>
@endsection
