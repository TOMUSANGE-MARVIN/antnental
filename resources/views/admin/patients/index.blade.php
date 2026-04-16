@extends('layouts.admin')

@section('title', 'Patients')
@section('header', 'Registered Patients')

@section('content')
<!-- Add New Patient Button -->
<div class="mb-6">
    <a href="{{ route('admin.patients.create') }}" 
       class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add New Patient
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-700">{{ $patients->total() }} patients registered</h2>
    </div>
    <div class="overflow-x-auto">
    <table class="min-w-[860px] w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">LMP Date</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">EDD</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Blood Group</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Weeks</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($patients as $patient)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-semibold">
                            {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $patient->user->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $patient->user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600 text-sm">
                    {{ $patient->lmp_date ? $patient->lmp_date->format('M j, Y') : '—' }}
                </td>
                <td class="px-6 py-4 text-gray-600 text-sm">
                    {{ $patient->edd_date ? $patient->edd_date->format('M j, Y') : '—' }}
                </td>
                <td class="px-6 py-4">
                    @if($patient->blood_group)
                        <span class="inline-block px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-sm font-medium">{{ $patient->blood_group }}</span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($patient->weeks_pregnant !== null)
                        <span class="inline-block px-2.5 py-1 bg-teal-50 text-teal-700 rounded-lg text-sm font-medium">{{ $patient->weeks_pregnant }}w</span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.patients.show', $patient) }}" class="text-teal-600 hover:text-teal-800 text-sm font-medium">View</a>
                        <a href="{{ route('admin.patients.edit', $patient) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    No patients registered yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($patients->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $patients->links() }}
    </div>
    @endif
</div>
@endsection
