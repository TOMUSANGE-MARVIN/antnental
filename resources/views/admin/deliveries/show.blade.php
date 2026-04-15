@extends('layouts.admin')

@section('title', 'Delivery Record Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.deliveries.index') }}" class="text-indigo-600 hover:text-indigo-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Delivery Record #{{ $delivery->id }}</h1>
                        <p class="text-gray-600">{{ $delivery->patient->user->name }} • {{ $delivery->delivery_datetime->format('F j, Y, g:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                        @if($delivery->delivery_outcome === 'successful') bg-green-100 text-green-800
                        @elseif($delivery->delivery_outcome === 'complicated') bg-yellow-100 text-yellow-800
                        @elseif($delivery->delivery_outcome === 'infant_death') bg-red-100 text-red-800
                        @elseif($delivery->delivery_outcome === 'maternal_death') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucwords(str_replace('_', ' ', $delivery->delivery_outcome)) }}
                    </span>
                    <a href="{{ route('admin.deliveries.edit', $delivery) }}" 
                       class="inline-flex items-center px-4 py-2 border border-indigo-300 text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Record
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Patient Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Patient Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Full Name</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">
                                    <a href="{{ route('admin.patients.show', $delivery->patient) }}" class="text-indigo-600 hover:text-indigo-700">
                                        {{ $delivery->patient->user->name }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Age</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($delivery->patient->date_of_birth)->age }} years
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Blood Group</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ $delivery->patient->blood_group ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Pregnancy History</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">G{{ $delivery->patient->gravida ?? 0 }}P{{ $delivery->patient->para ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Delivery Details
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Date & Time</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ $delivery->delivery_datetime->format('F j, Y, g:i A') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Delivery Type</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $delivery->delivery_type)) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Pregnancy Type</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ ucwords($delivery->pregnancy_type) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Number of Babies</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ $delivery->number_of_babies }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Location</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">
                                    {{ ucwords($delivery->delivery_location) }}
                                    @if($delivery->ward_room)
                                        • {{ $delivery->ward_room }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Surgery Performed</label>
                                <p class="mt-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $delivery->surgery_performed ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $delivery->surgery_performed ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if($delivery->surgery_performed && $delivery->surgery_details)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-500">Surgery Details</label>
                            <p class="mt-1 text-gray-900">{{ $delivery->surgery_details }}</p>
                        </div>
                        @endif

                        @if($delivery->complications)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="text-sm font-medium text-gray-500">Complications</label>
                            <p class="mt-1 text-gray-900">{{ $delivery->complications }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Baby Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            Baby Details
                        </h2>
                    </div>
                    <div class="p-6">
                        @php $babies = json_decode($delivery->babies_details, true); @endphp
                        <div class="space-y-6">
                            @foreach($babies as $index => $baby)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $index > 0 ? 'mt-4' : '' }}">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        Baby {{ $index + 1 }}
                                        @if($delivery->number_of_babies > 1)
                                            ({{ ucwords($delivery->pregnancy_type) }})
                                        @endif
                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($baby['status'] === 'alive') bg-green-100 text-green-800
                                        @elseif($baby['status'] === 'stillborn') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucwords($baby['status']) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Gender</label>
                                        <p class="mt-1 font-medium text-gray-900">{{ ucwords($baby['gender']) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Weight</label>
                                        <p class="mt-1 font-medium text-gray-900">{{ $baby['weight'] }} kg</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Length</label>
                                        <p class="mt-1 font-medium text-gray-900">{{ $baby['length'] }} cm</p>
                                    </div>
                                    @if(isset($baby['birth_time']))
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Birth Time</label>
                                        <p class="mt-1 font-medium text-gray-900">{{ $baby['birth_time'] }}</p>
                                    </div>
                                    @endif
                                </div>

                                @if(isset($baby['complications']) && $baby['complications'])
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <label class="text-sm font-medium text-gray-500">Complications</label>
                                    <p class="mt-1 text-gray-900">{{ $baby['complications'] }}</p>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Medical Notes -->
                @if($delivery->medical_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Medical Notes
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $delivery->medical_notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Maternal Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Maternal Status
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <p class="mt-1">
                                    <span class="px-2 py-1 text-sm font-medium rounded-full 
                                        @if($delivery->maternal_status === 'alive_healthy') bg-green-100 text-green-800
                                        @elseif($delivery->maternal_status === 'alive_complications') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucwords(str_replace('_', ' ', $delivery->maternal_status)) }}
                                    </span>
                                </p>
                            </div>

                            @if($delivery->maternal_complications)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Complications</label>
                                <p class="mt-1 text-gray-900">{{ $delivery->maternal_complications }}</p>
                            </div>
                            @endif

                            @if($delivery->discharge_datetime)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Discharge Date</label>
                                <p class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($delivery->discharge_datetime)->format('F j, Y, g:i A') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Medical Staff -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Medical Staff
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Attending Doctor</label>
                                <p class="mt-1 font-medium text-gray-900">{{ $delivery->doctor->user->name }}</p>
                            </div>
                            
                            @if($delivery->attending_physician && $delivery->attending_physician !== $delivery->doctor->user->name)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Attending Physician</label>
                                <p class="mt-1 font-medium text-gray-900">{{ $delivery->attending_physician }}</p>
                            </div>
                            @endif

                            @if($delivery->midwife_nurse)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Midwife/Nurse</label>
                                <p class="mt-1 font-medium text-gray-900">{{ $delivery->midwife_nurse }}</p>
                            </div>
                            @endif

                            <div>
                                <label class="text-sm font-medium text-gray-500">Recorded By</label>
                                <p class="mt-1 font-medium text-gray-900">{{ $delivery->admin->name ?? 'System' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Follow-up -->
                @if($delivery->requires_followup)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Follow-up Required
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($delivery->next_appointment_date)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Next Appointment</label>
                            <p class="mt-1 font-medium text-gray-900">{{ \Carbon\Carbon::parse($delivery->next_appointment_date)->format('F j, Y, g:i A') }}</p>
                        </div>
                        @endif

                        @if($delivery->post_delivery_notes)
                        <div class="mt-4">
                            <label class="text-sm font-medium text-gray-500">Follow-up Notes</label>
                            <p class="mt-1 text-gray-900">{{ $delivery->post_delivery_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Record Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Record Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Record ID:</span>
                                <span class="font-medium text-gray-900">#{{ $delivery->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Created:</span>
                                <span class="font-medium text-gray-900">{{ $delivery->created_at->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Updated:</span>
                                <span class="font-medium text-gray-900">{{ $delivery->updated_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection