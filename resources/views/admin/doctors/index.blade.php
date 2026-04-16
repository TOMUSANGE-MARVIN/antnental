@extends('layouts.admin')

@section('title', 'Doctors')
@section('header', 'Manage Doctors')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
    <p class="text-gray-500">{{ $doctors->total() }} doctors registered</p>
    <a href="{{ route('admin.doctors.create') }}" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl font-medium transition flex items-center justify-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Add Doctor</span>
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="min-w-[760px] w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Doctor</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Specialization</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($doctors as $doctor)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-semibold">
                            {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Dr. {{ $doctor->user->name }}</p>
                            <p class="text-gray-500 text-sm">{{ $doctor->user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-gray-700">{{ $doctor->specialization }}</p>
                    @if($doctor->qualification)
                        <p class="text-gray-400 text-sm">{{ $doctor->qualification }}</p>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $doctor->user->phone ?? '—' }}</td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $doctor->user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $doctor->user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.doctors.show', $doctor) }}" class="text-teal-600 hover:text-teal-800 text-sm font-medium">View</a>
                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this doctor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    No doctors registered yet.
                    <a href="{{ route('admin.doctors.create') }}" class="text-teal-600 hover:underline ml-1">Add the first doctor</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($doctors->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $doctors->links() }}
    </div>
    @endif
</div>
@endsection
