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

        <div class="bg-blue-700 px-6 py-4">
            <p class="text-blue-100 text-sm">Appointment ID #{{ $appointment->id }}</p>
            <p class="text-white font-semibold text-lg">Dr. {{ $appointment->doctor_name }}</p>
        </div>

        <div class="divide-y divide-gray-100">

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

            @if($appointment->notes)
                <div class="flex px-6 py-4">
                    <span class="w-40 text-sm font-semibold text-gray-500">Doctor's Notes</span>
                    <span class="text-sm text-gray-800">{{ $appointment->notes }}</span>
                </div>
            @endif

            <div class="flex px-6 py-4">
                <span class="w-40 text-sm font-semibold text-gray-500">Booked On</span>
                <span class="text-sm text-gray-800">
                    {{ $appointment->created_at->format('F d, Y \a\t h:i A') }}
                </span>
            </div>

        </div>

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

    {{-- ── STATUS TIMELINE ── --}}
    {{-- Visual indicator showing where this appointment sits in the workflow --}}
    {{-- Steps are always shown in order: Pending → Confirmed → Cancelled --}}
    {{-- The current status is highlighted in blue. Prior steps are gray. Future steps are empty. --}}
    <div class="max-w-2xl mt-6">
        <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">
            Appointment Status Timeline
        </h2>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-8 py-6">
            @php
                // Define the fixed order of steps in the timeline
                $steps = ['pending', 'confirmed', 'cancelled'];

                // Find the index position of the current status in the steps array
                // pending = 0, confirmed = 1, cancelled = 2
                $currentIndex = array_search($appointment->status, $steps);
            @endphp

            <div class="flex items-center">
                @foreach($steps as $i => $step)

                    {{-- ── STEP NODE ── --}}
                    <div class="flex flex-col items-center">

                        {{-- Circle indicator --}}
                        @if($i === $currentIndex)
                            {{-- CURRENT step: solid blue filled circle with white checkmark --}}
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        @elseif($i < $currentIndex)
                            {{-- PAST step: solid gray filled circle — this step was passed --}}
                            <div class="w-10 h-10 rounded-full bg-gray-400 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        @else
                            {{-- FUTURE step: empty circle with gray border — not reached yet --}}
                            <div class="w-10 h-10 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center">
                                <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                            </div>
                        @endif

                        {{-- Step label below the circle --}}
                        <span class="mt-2 text-xs font-semibold uppercase tracking-wide
                            {{ $i === $currentIndex
                                ? 'text-blue-700'
                                : ($i < $currentIndex ? 'text-gray-500' : 'text-gray-400') }}">
                            {{ ucfirst($step) }}
                        </span>

                    </div>

                    {{-- ── CONNECTOR LINE between steps (not after the last step) ── --}}
                    @if($i < count($steps) - 1)
                        <div class="flex-1 h-0.5 mx-2 mb-5
                            {{ $i < $currentIndex ? 'bg-gray-400' : 'bg-gray-200' }}">
                        </div>
                    @endif

                @endforeach
            </div>

            {{-- Explanatory caption below the timeline --}}
            <p class="mt-4 text-xs text-gray-400 text-center">
                This appointment is currently
                <span class="font-semibold
                    {{ $appointment->status === 'confirmed' ? 'text-green-600' :
                       ($appointment->status === 'cancelled' ? 'text-red-500' : 'text-yellow-600') }}">
                    {{ ucfirst($appointment->status) }}
                </span>.
                @if($appointment->status === 'pending')
                    Waiting for clinic confirmation.
                @elseif($appointment->status === 'confirmed')
                    Your appointment has been confirmed by the clinic.
                @else
                    This appointment has been cancelled.
                @endif
            </p>
        </div>
    </div>

</x-app-layout>