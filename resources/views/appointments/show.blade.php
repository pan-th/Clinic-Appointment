<x-app-layout>
    <x-slot name="title">Appointment Details</x-slot>

    {{-- ── BACK BUTTON ── --}}
    <div class="mb-4">
        <a href="{{ route('appointments.index') }}"
           class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Appointments
        </a>
    </div>

    {{-- ── PAGE HEADER ── --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Appointment Details</h1>

        {{-- Status Badge --}}
        @php
            $statusClasses = match($appointment->status) {
                'confirmed' => 'bg-green-100 text-green-700 border border-green-200',
                'cancelled' => 'bg-red-100 text-red-700 border border-red-200',
                default     => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
            };
        @endphp
        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClasses }}">
            {{ ucfirst($appointment->status) }}
        </span>
    </div>

    {{-- ── DETAIL CARD ── --}}
    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Card Header --}}
        <div class="bg-blue-700 px-6 py-4">
            <p class="text-blue-100 text-sm">Appointment ID #{{ $appointment->id }}</p>
            <p class="text-white font-semibold text-lg">Dr. {{ $appointment->doctor_name }}</p>
        </div>

        {{-- Detail Rows --}}
        <div class="divide-y divide-gray-100">

            {{-- Patient Name (admin only) --}}
            @if(auth()->user()->isAdmin())
                <div class="flex px-6 py-4">
                    <span class="w-40 text-sm font-semibold text-gray-500">Patient</span>
                    <span class="text-sm text-gray-800">{{ $appointment->patient->name }}</span>
                </div>
            @endif

            <div class="flex px-6 py-4">
                <span class="w-40 text-sm font-semibold text-gray-500">Doctor</span>
                <span class="text-sm text-gray-800">Dr. {{ $appointment->doctor_name }}</span>
            </div>

            <div class="flex px-6 py-4">
                <span class="w-40 text-sm font-semibold text-gray-500">Date</span>
                <span class="text-sm text-gray-800">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}
                </span>
            </div>

            <div class="flex px-6 py-4">
                <span class="w-40 text-sm font-semibold text-gray-500">Time</span>
                <span class="text-sm text-gray-800">
                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                </span>
            </div>

            <div class="flex px-6 py-4">
                <span class="w-40 text-sm font-semibold text-gray-500">Reason</span>
                <span class="text-sm text-gray-800">{{ $appointment->reason }}</span>
            </div>

            <div class="flex px-6 py-4">
                <span class="w-40 text-sm font-semibold text-gray-500">Booked On</span>
                <span class="text-sm text-gray-800">
                    {{ $appointment->created_at->format('F d, Y \a\t h:i A') }}
                </span>
            </div>

        </div>

        {{-- Action Buttons --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3">
            <a href="{{ route('appointments.edit', $appointment) }}"
               class="px-5 py-2 bg-amber-500 text-white text-sm font-semibold rounded-lg
                      hover:bg-amber-600 transition-colors duration-200">
                Edit Appointment
            </a>

            @if(auth()->user()->isAdmin() || $appointment->user_id === auth()->id())
                <form method="POST" action="{{ route('appointments.destroy', $appointment) }}"
                      onsubmit="return confirm('Delete this appointment permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg
                                   hover:bg-red-600 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            @endif
        </div>

    </div>
</x-app-layout>