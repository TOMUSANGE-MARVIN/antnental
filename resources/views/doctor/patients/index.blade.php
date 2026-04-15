@extends('layouts.doctor')

@section('title', 'My Patients')
@section('header', 'My Patients')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($patients as $patient)
    <a href="{{ route('doctor.patients.show', $patient) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition block">
        <div class="flex items-center space-x-4 mb-4">
            <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center text-pink-700 font-bold text-lg">
                {{ strtoupper(substr($patient->user->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $patient->user->name }}</p>
                <p class="text-gray-400 text-sm">{{ $patient->user->email }}</p>
            </div>
        </div>
        <div class="space-y-2 text-sm">
            @if($patient->trimester)
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Trimester</span>
                <span class="px-2 py-0.5 bg-teal-50 text-teal-700 rounded text-xs font-medium">{{ $patient->trimester }}</span>
            </div>
            @endif
            @if($patient->weeks_pregnant !== null)
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Weeks Pregnant</span>
                <span class="font-medium text-gray-700">{{ $patient->weeks_pregnant }} weeks</span>
            </div>
            @endif
            @if($patient->edd_date)
            <div class="flex items-center justify-between">
                <span class="text-gray-400">EDD</span>
                <span class="font-medium text-teal-600 text-xs">{{ $patient->edd_date->format('M j, Y') }}</span>
            </div>
            @endif
            @if($patient->blood_group)
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Blood Group</span>
                <span class="font-semibold text-red-600">{{ $patient->blood_group }}</span>
            </div>
            @endif
        </div>
    </a>
    @empty
    <div class="col-span-3 text-center py-16 text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        No patients yet. Patients will appear here once they book appointments with you.
    </div>
    @endforelse
</div>
@if($patients->hasPages())
<div class="mt-6">{{ $patients->links() }}</div>
@endif
@endsection
