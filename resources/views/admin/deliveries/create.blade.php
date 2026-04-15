@extends('layouts.admin')

@section('title', 'Record New Delivery')
@section('header', 'Record New Delivery')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.deliveries.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Patient and Doctor Selection -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Patient & Doctor Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Select Patient *</label>
                    <select name="patient_id" id="patient_id" required 
                            class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        <option value="">Choose a patient...</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" 
                                {{ (old('patient_id') == $patient->id || ($selectedPatient && $selectedPatient->id == $patient->id)) ? 'selected' : '' }}>
                            {{ $patient->user->name }} (ID: {{ $patient->id }})
                        </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">Attending Doctor *</label>
                    <select name="doctor_id" id="doctor_id" required 
                            class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        <option value="">Choose a doctor...</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->user->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Delivery Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Delivery Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="delivery_datetime" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date & Time *</label>
                    <input type="datetime-local" name="delivery_datetime" id="delivery_datetime" 
                           value="{{ old('delivery_datetime') }}" required
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('delivery_datetime')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="delivery_type" class="block text-sm font-medium text-gray-700 mb-2">Delivery Type *</label>
                    <select name="delivery_type" id="delivery_type" required 
                            class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        <option value="">Select delivery type...</option>
                        <option value="natural" {{ old('delivery_type') == 'natural' ? 'selected' : '' }}>Natural Delivery</option>
                        <option value="cesarean" {{ old('delivery_type') == 'cesarean' ? 'selected' : '' }}>Cesarean Section</option>
                        <option value="assisted" {{ old('delivery_type') == 'assisted' ? 'selected' : '' }}>Assisted Delivery</option>
                        <option value="emergency_cesarean" {{ old('delivery_type') == 'emergency_cesarean' ? 'selected' : '' }}>Emergency C-Section</option>
                    </select>
                    @error('delivery_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="delivery_outcome" class="block text-sm font-medium text-gray-700 mb-2">Delivery Outcome *</label>
                    <select name="delivery_outcome" id="delivery_outcome" required 
                            class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        <option value="">Select outcome...</option>
                        <option value="successful" {{ old('delivery_outcome') == 'successful' ? 'selected' : '' }}>Successful</option>
                        <option value="complicated" {{ old('delivery_outcome') == 'complicated' ? 'selected' : '' }}>Complicated</option>
                        <option value="maternal_death" {{ old('delivery_outcome') == 'maternal_death' ? 'selected' : '' }}>Maternal Death</option>
                        <option value="infant_death" {{ old('delivery_outcome') == 'infant_death' ? 'selected' : '' }}>Infant Death</option>
                        <option value="both_deaths" {{ old('delivery_outcome') == 'both_deaths' ? 'selected' : '' }}>Both Deaths</option>
                    </select>
                    @error('delivery_outcome')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="delivery_location" class="block text-sm font-medium text-gray-700 mb-2">Delivery Location *</label>
                    <input type="text" name="delivery_location" id="delivery_location" 
                           value="{{ old('delivery_location', 'Hospital') }}" required
                           placeholder="e.g., Hospital, Home, Clinic"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('delivery_location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="complications" class="block text-sm font-medium text-gray-700 mb-2">Complications (if any)</label>
                <textarea name="complications" id="complications" rows="3" 
                          placeholder="Describe any complications during delivery..."
                          class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">{{ old('complications') }}</textarea>
                @error('complications')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Surgery Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Surgery Information</h3>
            <div class="flex items-center mb-4">
                <input type="checkbox" name="surgery_performed" id="surgery_performed" value="1" 
                       {{ old('surgery_performed') ? 'checked' : '' }}
                       class="h-4 w-4 text-teal-600 focus:ring-teal-500 border border-gray-300 rounded">
                <label for="surgery_performed" class="ml-2 block text-sm text-gray-700">Surgery was performed</label>
            </div>
            
            <div id="surgery_details_section" class="hidden">
                <label for="surgery_details" class="block text-sm font-medium text-gray-700 mb-2">Surgery Details</label>
                <textarea name="surgery_details" id="surgery_details" rows="3" 
                          placeholder="Describe the surgical procedure..."
                          class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">{{ old('surgery_details') }}</textarea>
                @error('surgery_details')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Baby Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Baby Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="pregnancy_type" class="block text-sm font-medium text-gray-700 mb-2">Pregnancy Type *</label>
                    <select name="pregnancy_type" id="pregnancy_type" required 
                            class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        <option value="">Select pregnancy type...</option>
                        <option value="single" {{ old('pregnancy_type') == 'single' ? 'selected' : '' }}>Single Birth</option>
                        <option value="twins" {{ old('pregnancy_type') == 'twins' ? 'selected' : '' }}>Twins</option>
                        <option value="triplets" {{ old('pregnancy_type') == 'triplets' ? 'selected' : '' }}>Triplets</option>
                        <option value="quadruplets" {{ old('pregnancy_type') == 'quadruplets' ? 'selected' : '' }}>Quadruplets</option>
                        <option value="other_multiple" {{ old('pregnancy_type') == 'other_multiple' ? 'selected' : '' }}>Other Multiple</option>
                    </select>
                    @error('pregnancy_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="number_of_babies" class="block text-sm font-medium text-gray-700 mb-2">Number of Babies *</label>
                    <input type="number" name="number_of_babies" id="number_of_babies" 
                           value="{{ old('number_of_babies', 1) }}" min="1" max="10" required
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('number_of_babies')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div id="babies_details_container">
                <div class="baby-details bg-gray-50 rounded-lg p-4 mb-4">
                    <h4 class="font-medium text-gray-800 mb-3">Baby 1</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                            <select name="babies_details[0][gender]" required 
                                    class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                                <option value="">Select gender...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                            <input type="number" name="babies_details[0][weight]" step="0.1" min="0.1" max="10" 
                                   placeholder="e.g., 3.2"
                                   class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="babies_details[0][status]" required 
                                    class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                                <option value="">Select status...</option>
                                <option value="alive">Alive</option>
                                <option value="deceased">Deceased</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maternal Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Maternal Status</h3>
            <div class="mb-6">
                <label for="maternal_status" class="block text-sm font-medium text-gray-700 mb-2">Maternal Status *</label>
                <select name="maternal_status" id="maternal_status" required 
                        class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    <option value="">Select maternal status...</option>
                    <option value="alive_healthy" {{ old('maternal_status') == 'alive_healthy' ? 'selected' : '' }}>Alive & Healthy</option>
                    <option value="alive_complications" {{ old('maternal_status') == 'alive_complications' ? 'selected' : '' }}>Alive with Complications</option>
                    <option value="deceased" {{ old('maternal_status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                </select>
                @error('maternal_status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="maternal_complications_section" class="hidden">
                <label for="maternal_complications" class="block text-sm font-medium text-gray-700 mb-2">Maternal Complications</label>
                <textarea name="maternal_complications" id="maternal_complications" rows="3" 
                          placeholder="Describe complications..."
                          class="w-full rounded-lg border-gray-300 focus:border-teal-500 focus:ring-teal-500 mb-4">{{ old('maternal_complications') }}</textarea>
            </div>

            <div id="maternal_death_section" class="hidden space-y-4">
                <div>
                    <label for="maternal_death_time" class="block text-sm font-medium text-gray-700 mb-2">Time of Death</label>
                    <input type="datetime-local" name="maternal_death_time" id="maternal_death_time" 
                           value="{{ old('maternal_death_time') }}"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                </div>
                <div>
                    <label for="maternal_death_cause" class="block text-sm font-medium text-gray-700 mb-2">Cause of Death</label>
                    <textarea name="maternal_death_cause" id="maternal_death_cause" rows="3" 
                              placeholder="Cause of maternal death..."
                              class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">{{ old('maternal_death_cause') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Medical Staff & Notes -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Medical Staff & Notes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="attending_physician" class="block text-sm font-medium text-gray-700 mb-2">Attending Physician</label>
                    <input type="text" name="attending_physician" id="attending_physician" 
                           value="{{ old('attending_physician') }}" 
                           placeholder="Name of attending physician"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                </div>
                <div>
                    <label for="midwife_nurse" class="block text-sm font-medium text-gray-700 mb-2">Midwife/Nurse</label>
                    <input type="text" name="midwife_nurse" id="midwife_nurse" 
                           value="{{ old('midwife_nurse') }}" 
                           placeholder="Name of midwife or nurse"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                </div>
            </div>
            <div>
                <label for="medical_notes" class="block text-sm font-medium text-gray-700 mb-2">Medical Notes</label>
                <textarea name="medical_notes" id="medical_notes" rows="4" 
                          placeholder="Additional medical notes about the delivery..."
                          class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">{{ old('medical_notes') }}</textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 pb-6">
            <a href="{{ route('admin.deliveries.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">
                Record Delivery
            </button>
        </div>
    </form>
</div>

<script>
// Show/hide surgery details based on checkbox
document.getElementById('surgery_performed').addEventListener('change', function() {
    const section = document.getElementById('surgery_details_section');
    if (this.checked) {
        section.classList.remove('hidden');
    } else {
        section.classList.add('hidden');
    }
});

// Show/hide maternal sections based on status
document.getElementById('maternal_status').addEventListener('change', function() {
    const complicationsSection = document.getElementById('maternal_complications_section');
    const deathSection = document.getElementById('maternal_death_section');
    
    // Hide both sections first
    complicationsSection.classList.add('hidden');
    deathSection.classList.add('hidden');
    
    if (this.value === 'alive_complications') {
        complicationsSection.classList.remove('hidden');
    } else if (this.value === 'deceased') {
        complicationsSection.classList.remove('hidden');
        deathSection.classList.remove('hidden');
    }
});

// Update baby details forms based on number of babies
document.getElementById('number_of_babies').addEventListener('change', function() {
    const container = document.getElementById('babies_details_container');
    const count = parseInt(this.value) || 1;
    
    // Clear existing forms
    container.innerHTML = '';
    
    // Create forms for each baby
    for (let i = 0; i < count; i++) {
        const div = document.createElement('div');
        div.className = 'baby-details bg-gray-50 rounded-lg p-4 mb-4';
        div.innerHTML = `
            <h4 class="font-medium text-gray-800 mb-3">Baby ${i + 1}</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                    <select name="babies_details[${i}][gender]" required 
                            class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        <option value="">Select gender...</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" name="babies_details[${i}][weight]" step="0.1" min="0.1" max="10" 
                           placeholder="e.g., 3.2"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="babies_details[${i}][status]" required 
                            class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        <option value="">Select status...</option>
                        <option value="alive">Alive</option>
                        <option value="deceased">Deceased</option>
                    </select>
                </div>
            </div>
        `;
        container.appendChild(div);
    }
});

// Update pregnancy type when number of babies changes
document.getElementById('number_of_babies').addEventListener('change', function() {
    const pregnancyType = document.getElementById('pregnancy_type');
    const count = parseInt(this.value) || 1;
    
    if (count === 1) {
        pregnancyType.value = 'single';
    } else if (count === 2) {
        pregnancyType.value = 'twins';
    } else if (count === 3) {
        pregnancyType.value = 'triplets';
    } else if (count === 4) {
        pregnancyType.value = 'quadruplets';
    } else {
        pregnancyType.value = 'other_multiple';
    }
});

// Initialize forms on page load
document.addEventListener('DOMContentLoaded', function() {
    // Trigger change events to show/hide sections based on current values
    document.getElementById('surgery_performed').dispatchEvent(new Event('change'));
    document.getElementById('maternal_status').dispatchEvent(new Event('change'));
    document.getElementById('number_of_babies').dispatchEvent(new Event('change'));
});
</script>
@endsection