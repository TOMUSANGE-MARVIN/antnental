@extends('layouts.doctor')

@section('title', 'My Appointments')
@section('header', 'My Appointments')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-5 border-b border-gray-100">
        <p class="text-gray-500">{{ $appointments->total() }} appointments</p>
    </div>
    <div class="overflow-x-auto">
    <table class="min-w-[820px] w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($appointments as $appt)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-semibold text-sm">
                            {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 text-sm">{{ $appt->patient->user->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $appt->patient->user->phone ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $appt->appointment_date->format('M j, Y') }}<br>
                    <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 bg-purple-50 text-purple-700 rounded-lg text-xs font-medium">{{ $appt->type_display }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium
                        {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' :
                           ($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                           ($appt->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                        {{ ucfirst($appt->status) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('doctor.appointments.show', $appt) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</a>
                        <a href="{{ route('doctor.appointments.edit', $appt) }}" class="text-amber-600 hover:text-amber-800 text-sm font-medium">Edit</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No appointments found.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($appointments->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $appointments->links() }}
    </div>
    @endif
</div>
@endsection
