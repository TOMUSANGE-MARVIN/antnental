@extends('layouts.admin')

@section('title', 'Add New Patient')
@section('header', 'Add New Patient')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.patients.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Account Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           placeholder="Enter patient's full name"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                           placeholder="patient@example.com"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" 
                           placeholder="+256 xxx xxx xxx"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('date_of_birth')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" name="password" id="password" required 
                           placeholder="Minimum 8 characters"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                           placeholder="Confirm password"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Medical Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="blood_group" class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                    <select name="blood_group" id="blood_group" 
                            class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                        <option value="">Select blood group...</option>
                        <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                    </select>
                    @error('blood_group')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lmp_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Last Menstrual Period (LMP)
                        <span class="text-xs text-gray-500">- EDD will be calculated automatically</span>
                    </label>
                    <input type="date" name="lmp_date" id="lmp_date" value="{{ old('lmp_date') }}"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('lmp_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gravida" class="block text-sm font-medium text-gray-700 mb-2">
                        Gravida
                        <span class="text-xs text-gray-500">- Total number of pregnancies</span>
                    </label>
                    <input type="number" name="gravida" id="gravida" value="{{ old('gravida', 1) }}" min="0" max="20"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('gravida')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="para" class="block text-sm font-medium text-gray-700 mb-2">
                        Para
                        <span class="text-xs text-gray-500">- Number of births after 20 weeks</span>
                    </label>
                    <input type="number" name="para" id="para" value="{{ old('para', 0) }}" min="0" max="20"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('para')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Home Address</label>
                <textarea name="address" id="address" rows="3" 
                          placeholder="Enter patient's home address..."
                          class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">{{ old('address') }}</textarea>
                @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Emergency Contact Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Emergency Contact</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Name</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}" 
                           placeholder="Name of emergency contact person"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('emergency_contact')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="emergency_phone" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Phone</label>
                    <input type="tel" name="emergency_phone" id="emergency_phone" value="{{ old('emergency_phone') }}" 
                           placeholder="+256 xxx xxx xxx"
                           class="w-full rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-teal-500 px-3 py-2">
                    @error('emergency_phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Expected Delivery Date Display -->
        <div id="edd-display" class="bg-teal-50 rounded-2xl border border-teal-200 p-6 hidden">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-teal-800">Estimated Delivery Date (EDD)</h4>
                    <p id="edd-value" class="text-teal-700 font-medium"></p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 pb-6">
            <a href="{{ route('admin.patients.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">
                Create Patient
            </button>
        </div>
    </form>
</div>

<script>
// Calculate and display EDD when LMP is entered
document.getElementById('lmp_date').addEventListener('change', function() {
    const lmpDate = this.value;
    if (lmpDate) {
        const lmp = new Date(lmpDate);
        const edd = new Date(lmp.getTime() + (280 * 24 * 60 * 60 * 1000)); // Add 280 days
        
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        document.getElementById('edd-value').textContent = edd.toLocaleDateString('en-US', options);
        document.getElementById('edd-display').classList.remove('hidden');
    } else {
        document.getElementById('edd-display').classList.add('hidden');
    }
});

// Auto-calculate Para based on Gravida (basic validation)
document.getElementById('gravida').addEventListener('change', function() {
    const gravida = parseInt(this.value) || 0;
    const paraInput = document.getElementById('para');
    const currentPara = parseInt(paraInput.value) || 0;
    
    // Ensure Para doesn't exceed Gravida
    if (currentPara > gravida) {
        paraInput.value = gravida;
    }
    
    // Set max attribute for Para
    paraInput.setAttribute('max', gravida);
});

// Validate Para doesn't exceed Gravida
document.getElementById('para').addEventListener('change', function() {
    const para = parseInt(this.value) || 0;
    const gravida = parseInt(document.getElementById('gravida').value) || 0;
    
    if (para > gravida) {
        this.value = gravida;
        alert('Para cannot be greater than Gravida');
    }
});

// Auto-fill email based on name (optional convenience feature)
document.getElementById('name').addEventListener('blur', function() {
    const emailInput = document.getElementById('email');
    if (!emailInput.value && this.value) {
        const nameParts = this.value.toLowerCase().split(' ');
        const suggestedEmail = nameParts.join('.') + '@mamacare.com';
        emailInput.placeholder = suggestedEmail + ' (suggested)';
    }
});
</script>
@endsection