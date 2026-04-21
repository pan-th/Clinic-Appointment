<x-app-layout>
    <x-slot name="title">My Appointments</x-slot>

    {{-- ── PAGE HEADER ── --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Schedule</h1>
            <p class="text-sm text-gray-500 mt-1">
                Appointments assigned to Dr. {{ auth()->user()->name }}.
            </p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
            Doctor View
        </span>
    </div>

    {{-- ── APPOINTMENTS LIST ── --}}
    @if($appointments->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-500 text-lg font-medium">No appointments assigned to you yet.</p>
            <p class="text-gray-400 text-sm mt-1">
                Appointments appear here when a patient books with Dr. {{ auth()->user()->name }}.
            </p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($appointments as $appointment)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                    {{-- Card Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-700 font-bold text-sm">
                                    {{ strtoupper(substr($appointment->patient->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $appointment->patient->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}
                                    at
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </p>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        @php
                            $statusClasses = match($appointment->status) {
                                'confirmed' => 'bg-green-100 text-green-700 border border-green-200',
                                'cancelled' => 'bg-red-100 text-red-700 border border-red-200',
                                default     => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                            };
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusClasses }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    {{-- Reason --}}
                    <div class="px-6 py-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                            Reason for Visit
                        </p>
                        <p class="text-sm text-gray-800">{{ $appointment->reason }}</p>
                    </div>

                    {{-- Consultation Notes Section --}}
                    <div class="px-6 pb-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Consultation Notes
                        </p>

                        @if($appointment->notes)
                            {{-- Existing Note Display --}}
                            <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3 mb-3">
                                <p class="text-sm text-gray-800">{{ $appointment->notes }}</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic mb-3">No notes added yet.</p>
                        @endif

                        {{-- Add / Update Note Form --}}
                        <form method="POST"
                              action="{{ route('doctor.appointments.addNote', $appointment) }}">
                            @csrf
                            @method('PATCH')
                            <textarea name="notes"
                                      rows="3"
                                      placeholder="Write your consultation notes here..."
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                                             focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                                             @error('notes') border-red-400 bg-red-50 @enderror">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-2">
                                <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white text-sm font-semibold
                                               rounded-lg hover:bg-green-700 transition-colors">
                                    {{ $appointment->notes ? 'Update Note' : 'Save Note' }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>