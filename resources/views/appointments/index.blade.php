<x-app-layout>
    <x-slot name="title">My Appointments</x-slot>

    {{-- ── PAGE HEADER ── --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                {{ auth()->user()->isAdmin() ? 'All Appointments' : 'My Appointments' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ auth()->user()->isAdmin()
                    ? 'Viewing all patient appointments in the system.'
                    : 'Manage your clinic appointments below.' }}
            </p>
        </div>

        {{-- Add New Appointment Button --}}
        <a href="{{ route('appointments.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm
                  font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Book Appointment
        </a>
    </div>

    {{-- ── APPOINTMENTS TABLE ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($appointments->isEmpty())
            {{-- Empty State --}}
            <div class="text-center py-16">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 text-lg font-medium">No appointments found.</p>
                <p class="text-gray-400 text-sm mt-1">Click "Book Appointment" to schedule your first visit.</p>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-blue-700">
                    <tr>
                        {{-- Admin sees an extra "Patient" column --}}
                        @if(auth()->user()->isAdmin())
                            <th class="px-6 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                                Patient
                            </th>
                        @endif
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
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-blue-100 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @foreach($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- Patient Name (Admin only) --}}
                            @if(auth()->user()->isAdmin())
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
                            @endif

                            {{-- Doctor Name --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                Dr. {{ $appointment->doctor_name }}
                            </td>

                            {{-- Appointment Date --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            </td>

                            {{-- Appointment Time --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    // Choose badge color based on status value
                                    $statusClasses = match($appointment->status) {
                                        'confirmed'  => 'bg-green-100 text-green-700 border border-green-200',
                                        'cancelled'  => 'bg-red-100 text-red-700 border border-red-200',
                                        default      => 'bg-yellow-100 text-yellow-700 border border-yellow-200', // pending
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>

                            {{-- Action Buttons --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- View Button (everyone can see this) --}}
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                       class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50
                                              rounded hover:bg-blue-100 transition-colors">
                                        View
                                    </a>

                                    {{-- Edit Button --}}
                                    <a href="{{ route('appointments.edit', $appointment) }}"
                                       class="px-3 py-1 text-xs font-medium text-amber-600 bg-amber-50
                                              rounded hover:bg-amber-100 transition-colors">
                                        Edit
                                    </a>

                                    {{-- Delete Button (show only for admin, or patient's own record) --}}
                                    @if(auth()->user()->isAdmin() || $appointment->user_id === auth()->id())
                                        <form method="POST"
                                              action="{{ route('appointments.destroy', $appointment) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this appointment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 text-xs font-medium text-red-600 bg-red-50
                                                           rounded hover:bg-red-100 transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>