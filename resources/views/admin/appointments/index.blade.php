@extends('layouts.admin')

@section('title', 'Appointments')
@section('header', 'All Appointments')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-gray-100">
        <p class="text-gray-500">{{ $appointments->total() }} total appointments</p>
    </div>
    <div class="overflow-x-auto">
    <table class="min-w-[980px] w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Doctor</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reminder</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($appointments as $appt)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <a href="{{ route('admin.patients.show', $appt->patient) }}" class="flex items-center space-x-2 group">
                        <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-semibold text-xs">
                            {{ strtoupper(substr($appt->patient->user->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-teal-600">{{ $appt->patient->user->name }}</span>
                    </a>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">Dr. {{ $appt->doctor->user->name }}</td>
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
                    <span class="inline-block px-2 py-0.5 rounded text-xs {{ $appt->reminder_sent ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-400' }}">
                        {{ $appt->reminder_sent ? 'Sent' : 'Pending' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.appointments.edit', $appt) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">No appointments found.</td>
            </tr>
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
