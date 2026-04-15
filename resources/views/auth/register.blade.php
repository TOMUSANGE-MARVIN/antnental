@extends('layouts.app')

@section('title', 'Patient Registration')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 to-pink-50 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-flex items-center space-x-2 mb-4">
                <svg class="w-10 h-10 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/>
                </svg>
                <span class="text-3xl font-bold text-teal-700">MamaCare</span>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Create Your Patient Account</h2>
            <p class="text-gray-500 mt-1">Begin your antenatal care journey with us</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="w-7 h-7 bg-teal-100 text-teal-700 rounded-full flex items-center justify-center text-sm font-bold mr-2">1</span>
                        Personal Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror"
                                   placeholder="Enter your full name">
                            @error('name') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-400 @enderror"
                                   placeholder="you@example.com">
                            @error('email') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('phone') border-red-400 @enderror"
                                   placeholder="+1 234 567 8900">
                            @error('phone') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password *</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('password') border-red-400 @enderror"
                                   placeholder="Min. 8 characters">
                            @error('password') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password *</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                                   placeholder="Re-enter password">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Date of Birth *</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('date_of_birth') border-red-400 @enderror">
                            @error('date_of_birth') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Pregnancy Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="w-7 h-7 bg-pink-100 text-pink-700 rounded-full flex items-center justify-center text-sm font-bold mr-2">2</span>
                        Pregnancy Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Last Menstrual Period (LMP) *</label>
                            <input type="date" name="lmp_date" value="{{ old('lmp_date') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('lmp_date') border-red-400 @enderror">
                            <p class="text-xs text-gray-400 mt-1">EDD will be auto-calculated (LMP + 280 days)</p>
                            @error('lmp_date') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Blood Group</label>
                            <select name="blood_group"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('blood_group') border-red-400 @enderror">
                                <option value="">Select blood group</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Gravida (Number of Pregnancies)</label>
                            <input type="number" name="gravida" value="{{ old('gravida', 1) }}" min="1"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Para (Number of Births)</label>
                            <input type="number" name="para" value="{{ old('para', 0) }}" min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Home Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                                      placeholder="Street, City, State">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="w-7 h-7 bg-red-100 text-red-700 rounded-full flex items-center justify-center text-sm font-bold mr-2">3</span>
                        Emergency Contact
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Name *</label>
                            <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('emergency_contact') border-red-400 @enderror"
                                   placeholder="Spouse / Parent / Guardian">
                            @error('emergency_contact') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Phone *</label>
                            <input type="text" name="emergency_phone" value="{{ old('emergency_phone') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('emergency_phone') border-red-400 @enderror"
                                   placeholder="+1 234 567 8900">
                            @error('emergency_phone') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3.5 rounded-xl transition shadow-sm text-lg">
                    Create My Account
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">Already have an account?
                    <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-700 font-medium">Sign In</a>
                </p>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('landing') }}" class="text-gray-400 hover:text-teal-600 text-sm transition">← Back to Home</a>
        </div>
    </div>
</div>
@endsection
