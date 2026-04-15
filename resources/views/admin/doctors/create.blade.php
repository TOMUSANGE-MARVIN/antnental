@extends('layouts.admin')

@section('title', 'Add Doctor')
@section('header', 'Register New Doctor')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('admin.doctors.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror"
                           placeholder="Dr. John Smith">
                    @error('name') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-400 @enderror"
                           placeholder="doctor@hospital.com">
                    @error('email') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="+1 234 567 8900">
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

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Specialization *</label>
                    <input type="text" name="specialization" value="{{ old('specialization', 'Obstetrics & Gynecology') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 @error('specialization') border-red-400 @enderror"
                           placeholder="Obstetrics & Gynecology">
                    @error('specialization') <p class="mt-1 text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Qualification</label>
                    <input type="text" name="qualification" value="{{ old('qualification') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="MBBS, MD, FRCOG">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">License Number</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="MED-12345">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Available Days</label>
                    <input type="text" name="available_days" value="{{ old('available_days') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="Mon-Fri, 9AM-5PM">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Bio</label>
                    <textarea name="bio" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500"
                              placeholder="Brief professional biography">{{ old('bio') }}</textarea>
                </div>
            </div>

            <div class="flex items-center space-x-4 pt-2">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-8 py-2.5 rounded-xl transition">
                    Register Doctor
                </button>
                <a href="{{ route('admin.doctors.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
