@extends('layouts.admin')

@section('title', 'Patient Profile')
@section('header', 'Patient Profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Patient Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3 text-pink-600 text-3xl font-bold">
                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ $patient->user->name }}</h2>
                <p class="text-gray-500">{{ $patient->user->email }}</p>
                @if($patient->trimester)
                    <span class="inline-block mt-2 px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-medium">
                        {{ $patient->trimester }}
                    </span>
                @endif
            </div>
            <div class="space-y-3 text-sm">
                @if($patient->user->phone)
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Phone</span>
                    <span>{{ $patient->user->phone }}</span>
                </div>
                @endif
                @if($patient->date_of_birth)
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Date of Birth</span>
                    <span>{{ $patient->date_of_birth->format('M j, Y') }}</span>
                </div>
                @endif
                @if($patient->blood_group)
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Blood Group</span>
                    <span class="font-semibold text-red-600">{{ $patient->blood_group }}</span>
                </div>
                @endif
                @if($patient->lmp_date)
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">LMP</span>
                    <span>{{ $patient->lmp_date->format('M j, Y') }}</span>
                </div>
                @endif
                @if($patient->edd_date)
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">EDD</span>
                    <span class="font-semibold text-teal-600">{{ $patient->edd_date->format('M j, Y') }}</span>
                </div>
                @endif
                @if($patient->weeks_pregnant !== null)
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Weeks Pregnant</span>
                    <span class="font-semibold">{{ $patient->weeks_pregnant }} weeks</span>
                </div>
                @endif
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Gravida / Para</span>
                    <span>G{{ $patient->gravida }} P{{ $patient->para }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-3">Emergency Contact</h3>
            <p class="text-gray-800 font-medium">{{ $patient->emergency_contact ?? '—' }}</p>
            <p class="text-gray-500 text-sm">{{ $patient->emergency_phone ?? '' }}</p>
        </div>

        @if($patient->address)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-2">Address</h3>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $patient->address }}</p>
        </div>
        @endif
    </div>

    <!-- Appointments -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Appointments ({{ $patient->appointments->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($patient->appointments as $appt)
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-semibold text-sm">
                            {{ strtoupper(substr($appt->doctor->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 text-sm">Dr. {{ $appt->doctor->user->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $appt->type_display }} · {{ $appt->appointment_date->format('M j, Y') }} {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</p>
                        </div>
                    </div>
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium
                        {{ $appt->status === 'completed' ? 'bg-green-100 text-green-700' :
                           ($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                           ($appt->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                        {{ ucfirst($appt->status) }}
                    </span>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">No appointments yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Delivery Records -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Delivery Records</h3>
                <a href="{{ route('admin.deliveries.create', ['patient_id' => $patient->id]) }}" 
                   class="text-xs bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded-lg transition">
                    Record Delivery
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($patient->deliveryRecords as $delivery)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold
                                    {{ $delivery->delivery_outcome === 'successful' ? 'bg-green-100 text-green-700' : 
                                       ($delivery->delivery_outcome === 'complicated' ? 'bg-yellow-100 text-yellow-700' : 
                                       'bg-red-100 text-red-700') }}">
                            {{ $delivery->number_of_babies }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 text-sm">{{ $delivery->delivery_datetime->format('M j, Y') }}</p>
                            <p class="text-gray-400 text-xs">
                                {{ $delivery->delivery_type_display }} · 
                                {{ $delivery->pregnancy_type_display }} · 
                                Dr. {{ $delivery->doctor->user->name }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                {{ $delivery->delivery_outcome === 'successful' ? 'bg-green-100 text-green-700' : 
                                   ($delivery->delivery_outcome === 'complicated' ? 'bg-yellow-100 text-yellow-700' : 
                                   'bg-red-100 text-red-700') }}">
                            {{ $delivery->delivery_outcome_display }}
                        </span>
                        <div class="text-xs text-gray-500 mt-1">
                            Live: {{ $delivery->getTotalLiveBabies() }}
                            @if($delivery->getTotalDeceasedBabies() > 0)
                            | Deaths: {{ $delivery->getTotalDeceasedBabies() }}
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    No delivery records yet.
                    <br>
                    <a href="{{ route('admin.deliveries.create', ['patient_id' => $patient->id]) }}" 
                       class="text-teal-600 hover:underline text-sm">Record first delivery</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Buttons -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    <a href="{{ route('admin.patients.edit', $patient) }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-4 flex items-center space-x-3 transition shadow-sm">
        <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        <div>
            <p class="font-semibold">Edit Patient</p>
            <p class="text-blue-200 text-sm">Update patient information</p>
        </div>
    </a>
    <a href="{{ route('admin.deliveries.create', ['patient_id' => $patient->id]) }}" 
       class="bg-teal-600 hover:bg-teal-700 text-white rounded-xl p-4 flex items-center space-x-3 transition shadow-sm">
        <svg class="w-6 h-6 text-teal-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
        </svg>
        <div>
            <p class="font-semibold">Record Delivery</p>
            <p class="text-teal-200 text-sm">Add new delivery record</p>
        </div>
    </a>
</div>

<div class="mt-4">
    <a href="{{ route('admin.patients.index') }}" class="text-gray-500 hover:text-teal-600 text-sm transition">← Back to Patients</a>
</div>
@endsection
