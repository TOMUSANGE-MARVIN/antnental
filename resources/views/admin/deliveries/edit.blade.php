@extends('layouts.admin')

@section('title', 'Edit Delivery Record')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.deliveries.show', $delivery) }}" class="text-indigo-600 hover:text-indigo-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Delivery Record</h1>
                        <p class="text-gray-600">Record #{{ $delivery->id }} • {{ $delivery->patient->user->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.deliveries.update', $delivery) }}" method="POST" id="deliveryForm">
            @csrf
            @method('PUT')
            
            <!-- Patient and Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Selection -->
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                            <select name="patient_id" id="patient_id" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $delivery->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->user->name }} ({{ $patient->user->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Doctor Selection -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">Attending Doctor *</label>
                            <select name="doctor_id" id="doctor_id" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $delivery->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->name }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Delivery DateTime -->
                        <div>
                            <label for="delivery_datetime" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date & Time *</label>
                            <input type="datetime-local" name="delivery_datetime" id="delivery_datetime" required
                                   value="{{ $delivery->delivery_datetime->format('Y-m-d\TH:i') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Delivery Type -->
                        <div>
                            <label for="delivery_type" class="block text-sm font-medium text-gray-700 mb-2">Delivery Type *</label>
                            <select name="delivery_type" id="delivery_type" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Delivery Type</option>
                                <option value="natural" {{ $delivery->delivery_type == 'natural' ? 'selected' : '' }}>Natural Birth</option>
                                <option value="cesarean" {{ $delivery->delivery_type == 'cesarean' ? 'selected' : '' }}>Planned Cesarean Section</option>
                                <option value="emergency_cesarean" {{ $delivery->delivery_type == 'emergency_cesarean' ? 'selected' : '' }}>Emergency Cesarean Section</option>
                                <option value="assisted" {{ $delivery->delivery_type == 'assisted' ? 'selected' : '' }}>Assisted Delivery</option>
                            </select>
                        </div>

                        <!-- Delivery Outcome -->
                        <div>
                            <label for="delivery_outcome" class="block text-sm font-medium text-gray-700 mb-2">Delivery Outcome *</label>
                            <select name="delivery_outcome" id="delivery_outcome" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Outcome</option>
                                <option value="successful" {{ $delivery->delivery_outcome == 'successful' ? 'selected' : '' }}>Successful</option>
                                <option value="complicated" {{ $delivery->delivery_outcome == 'complicated' ? 'selected' : '' }}>Complicated</option>
                                <option value="maternal_death" {{ $delivery->delivery_outcome == 'maternal_death' ? 'selected' : '' }}>Maternal Death</option>
                                <option value="infant_death" {{ $delivery->delivery_outcome == 'infant_death' ? 'selected' : '' }}>Infant Death</option>
                                <option value="both_deaths" {{ $delivery->delivery_outcome == 'both_deaths' ? 'selected' : '' }}>Both Deaths</option>
                            </select>
                        </div>

                        <!-- Pregnancy Type -->
                        <div>
                            <label for="pregnancy_type" class="block text-sm font-medium text-gray-700 mb-2">Pregnancy Type *</label>
                            <select name="pregnancy_type" id="pregnancy_type" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Type</option>
                                <option value="single" {{ $delivery->pregnancy_type == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="twins" {{ $delivery->pregnancy_type == 'twins' ? 'selected' : '' }}>Twins</option>
                                <option value="triplets" {{ $delivery->pregnancy_type == 'triplets' ? 'selected' : '' }}>Triplets</option>
                                <option value="quadruplets" {{ $delivery->pregnancy_type == 'quadruplets' ? 'selected' : '' }}>Quadruplets</option>
                                <option value="other_multiple" {{ $delivery->pregnancy_type == 'other_multiple' ? 'selected' : '' }}>Other Multiple</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Surgery Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Surgery Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Surgery Performed -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="surgery_performed" value="1" 
                                       {{ $delivery->surgery_performed ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Surgery was performed during delivery</span>
                            </label>
                        </div>

                        <!-- Surgery Details -->
                        <div>
                            <label for="surgery_details" class="block text-sm font-medium text-gray-700 mb-2">Surgery Details</label>
                            <textarea name="surgery_details" id="surgery_details" rows="3" 
                                      placeholder="Describe the surgical procedure performed..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('surgery_details', $delivery->surgery_details) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baby Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Baby Information</h2>
                        <button type="button" onclick="addBaby()" 
                                class="inline-flex items-center px-3 py-2 border border-indigo-300 text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Baby
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <input type="hidden" name="number_of_babies" id="number_of_babies" value="{{ $delivery->number_of_babies }}">
                    
                    <div id="babies-container">
                        @php $babies = json_decode($delivery->babies_details, true); @endphp
                        @foreach($babies as $index => $baby)
                        <div class="baby-details border border-gray-200 rounded-lg p-4 mb-4" data-baby-index="{{ $index }}">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Baby {{ $index + 1 }}</h3>
                                <button type="button" onclick="removeBaby(this)" 
                                        class="text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                                    <select name="babies[{{ $index }}][gender]" required 
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ $baby['gender'] == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $baby['gender'] == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg) *</label>
                                    <input type="number" name="babies[{{ $index }}][weight]" step="0.1" min="0.1" max="10" required
                                           value="{{ $baby['weight'] ?? '' }}"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Length (cm) *</label>
                                    <input type="number" name="babies[{{ $index }}][length]" min="25" max="70" required
                                           value="{{ $baby['length'] ?? '' }}"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                    <select name="babies[{{ $index }}][status]" required 
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Status</option>
                                        <option value="alive" {{ $baby['status'] == 'alive' ? 'selected' : '' }}>Alive & Healthy</option>
                                        <option value="deceased" {{ $baby['status'] == 'deceased' ? 'selected' : '' }}>Deceased After Birth</option>
                                        <option value="stillborn" {{ $baby['status'] == 'stillborn' ? 'selected' : '' }}>Stillborn</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Birth Time</label>
                                    <input type="time" name="babies[{{ $index }}][birth_time]" 
                                           value="{{ $baby['birth_time'] ?? '' }}"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Complications</label>
                                    <textarea name="babies[{{ $index }}][complications]" rows="2" 
                                              placeholder="Any complications or special notes for this baby..."
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ $baby['complications'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Maternal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Maternal Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Maternal Status -->
                        <div>
                            <label for="maternal_status" class="block text-sm font-medium text-gray-700 mb-2">Maternal Status *</label>
                            <select name="maternal_status" id="maternal_status" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Status</option>
                                <option value="alive_healthy" {{ $delivery->maternal_status == 'alive_healthy' ? 'selected' : '' }}>Alive & Healthy</option>
                                <option value="alive_complications" {{ $delivery->maternal_status == 'alive_complications' ? 'selected' : '' }}>Alive with Complications</option>
                                <option value="deceased" {{ $delivery->maternal_status == 'deceased' ? 'selected' : '' }}>Deceased</option>
                            </select>
                        </div>

                        <!-- Discharge Date -->
                        <div>
                            <label for="discharge_datetime" class="block text-sm font-medium text-gray-700 mb-2">Discharge Date & Time</label>
                            <input type="datetime-local" name="discharge_datetime" id="discharge_datetime" 
                                   value="{{ $delivery->discharge_datetime ? \Carbon\Carbon::parse($delivery->discharge_datetime)->format('Y-m-d\TH:i') : '' }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Maternal Complications -->
                        <div class="md:col-span-2">
                            <label for="maternal_complications" class="block text-sm font-medium text-gray-700 mb-2">Maternal Complications</label>
                            <textarea name="maternal_complications" id="maternal_complications" rows="3" 
                                      placeholder="Describe any maternal complications..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('maternal_complications', $delivery->maternal_complications) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Additional Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Complications -->
                        <div class="md:col-span-2">
                            <label for="complications" class="block text-sm font-medium text-gray-700 mb-2">General Complications</label>
                            <textarea name="complications" id="complications" rows="3" 
                                      placeholder="Describe any complications during delivery..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('complications', $delivery->complications) }}</textarea>
                        </div>

                        <!-- Attending Physician -->
                        <div>
                            <label for="attending_physician" class="block text-sm font-medium text-gray-700 mb-2">Attending Physician</label>
                            <input type="text" name="attending_physician" id="attending_physician" 
                                   value="{{ old('attending_physician', $delivery->attending_physician) }}"
                                   placeholder="Name of the attending physician"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Midwife/Nurse -->
                        <div>
                            <label for="midwife_nurse" class="block text-sm font-medium text-gray-700 mb-2">Midwife/Nurse</label>
                            <input type="text" name="midwife_nurse" id="midwife_nurse" 
                                   value="{{ old('midwife_nurse', $delivery->midwife_nurse) }}"
                                   placeholder="Name of the midwife or nurse"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Ward/Room -->
                        <div>
                            <label for="ward_room" class="block text-sm font-medium text-gray-700 mb-2">Ward/Room</label>
                            <input type="text" name="ward_room" id="ward_room" 
                                   value="{{ old('ward_room', $delivery->ward_room) }}"
                                   placeholder="e.g., Maternity Ward A-101"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Delivery Location -->
                        <div>
                            <label for="delivery_location" class="block text-sm font-medium text-gray-700 mb-2">Delivery Location</label>
                            <select name="delivery_location" id="delivery_location" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="hospital" {{ $delivery->delivery_location == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                <option value="clinic" {{ $delivery->delivery_location == 'clinic' ? 'selected' : '' }}>Clinic</option>
                                <option value="home" {{ $delivery->delivery_location == 'home' ? 'selected' : '' }}>Home</option>
                                <option value="other" {{ $delivery->delivery_location == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Medical Notes -->
                        <div class="md:col-span-2">
                            <label for="medical_notes" class="block text-sm font-medium text-gray-700 mb-2">Medical Notes</label>
                            <textarea name="medical_notes" id="medical_notes" rows="4" 
                                      placeholder="Additional medical notes and observations..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('medical_notes', $delivery->medical_notes) }}</textarea>
                        </div>

                        <!-- Follow-up Required -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="requires_followup" value="1" 
                                       {{ $delivery->requires_followup ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Follow-up appointment required</span>
                            </label>
                        </div>

                        <!-- Next Appointment -->
                        <div>
                            <label for="next_appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Next Appointment Date</label>
                            <input type="datetime-local" name="next_appointment_date" id="next_appointment_date" 
                                   value="{{ $delivery->next_appointment_date ? \Carbon\Carbon::parse($delivery->next_appointment_date)->format('Y-m-d\TH:i') : '' }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Post Delivery Notes -->
                        <div class="md:col-span-2">
                            <label for="post_delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">Post-Delivery Notes</label>
                            <textarea name="post_delivery_notes" id="post_delivery_notes" rows="3" 
                                      placeholder="Notes for follow-up care and observations..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('post_delivery_notes', $delivery->post_delivery_notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                    Update Delivery Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let babyCount = {{ count(json_decode($delivery->babies_details, true)) }};

function addBaby() {
    const container = document.getElementById('babies-container');
    const babyDiv = document.createElement('div');
    babyDiv.className = 'baby-details border border-gray-200 rounded-lg p-4 mb-4';
    babyDiv.setAttribute('data-baby-index', babyCount);
    
    babyDiv.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Baby ${babyCount + 1}</h3>
            <button type="button" onclick="removeBaby(this)" class="text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                <select name="babies[${babyCount}][gender]" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg) *</label>
                <input type="number" name="babies[${babyCount}][weight]" step="0.1" min="0.1" max="10" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Length (cm) *</label>
                <input type="number" name="babies[${babyCount}][length]" min="25" max="70" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="babies[${babyCount}][status]" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select Status</option>
                    <option value="alive">Alive & Healthy</option>
                    <option value="deceased">Deceased After Birth</option>
                    <option value="stillborn">Stillborn</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Birth Time</label>
                <input type="time" name="babies[${babyCount}][birth_time]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Complications</label>
                <textarea name="babies[${babyCount}][complications]" rows="2" placeholder="Any complications or special notes for this baby..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
        </div>
    `;
    
    container.appendChild(babyDiv);
    babyCount++;
    updateBabyCount();
}

function removeBaby(button) {
    const babyDiv = button.closest('.baby-details');
    babyDiv.remove();
    updateBabyCount();
    updateBabyNumbers();
}

function updateBabyCount() {
    const babyDivs = document.querySelectorAll('.baby-details');
    document.getElementById('number_of_babies').value = babyDivs.length;
}

function updateBabyNumbers() {
    const babyDivs = document.querySelectorAll('.baby-details');
    babyDivs.forEach((div, index) => {
        const title = div.querySelector('h3');
        title.textContent = `Baby ${index + 1}`;
    });
}
</script>
@endsection