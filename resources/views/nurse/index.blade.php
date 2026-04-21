<x-app-layout>
    <x-slot name="title">Nurse — Appointment Queue</x-slot>

    {{-- ── PAGE HEADER ── --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Appointment Queue</h1>
            <p class="text-sm text-gray-500 mt-1">
                Update appointment statuses for today's clinic schedule.
            </p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
            Nurse View
        </span>
    </div>

    {{-- ── APPOINTMENT TABLE ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        @if($appointments->isEmpty())
            <div class="text-center py-16">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 text-lg font-medium">No appointments in the queue.</p>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-blue-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                            Patient
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                            Doctor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                            Time
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                            Reason
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                            Current Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-blue-100 uppercase tracking-wider">
                            Update Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @foreach($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- Patient Name --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-xs">
                                            {{ strtoupper(substr($appointment->patient->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ $appointment->patient->name }}
                                    </span>
                                </div>
                            </td>

                            {{-- Doctor Name --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                Dr. {{ $appointment->doctor_name }}
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            </td>

                            {{-- Time --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </td>

                            {{-- Reason --}}
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">
                                {{ $appointment->reason }}
                            </td>

                            {{-- Current Status Badge --}}
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>

                            {{-- Status Update Form --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <form method="POST"
                                      action="{{ route('nurse.appointments.updateStatus', $appointment) }}"
                                      class="flex items-center justify-end gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status"
                                            class="text-sm border border-gray-300 rounded-lg px-2 py-1.5
                                                   focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="confirmed"
                                            {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>
                                            Confirmed
                                        </option>
                                        <option value="cancelled"
                                            {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                                    </select>
                                    <button type="submit"
                                            class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold
                                                   rounded-lg hover:bg-blue-700 transition-colors">
                                        Update
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>