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

        <a href="{{ route('appointments.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm
                  font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Book Appointment
        </a>
    </div>

    {{-- ── SEARCH AND FILTER BAR ── --}}
    {{-- Submits as a GET request so filters appear in the URL --}}
    {{-- This means the browser back button and page refresh preserve the filter state --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-5">
        <form method="GET" action="{{ route('appointments.index') }}"
              class="flex flex-col sm:flex-row gap-3 items-end">

            {{-- Doctor Name Search --}}
            <div class="flex-1">
                <label for="search" class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">
                    Search by Doctor
                </label>
                <input type="text"
                       name="search"
                       id="search"
                       value="{{ $search ?? '' }}"
                       placeholder="e.g. Dr. Santos"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Status Filter Dropdown --}}
            <div class="w-full sm:w-48">
                <label for="status" class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">
                    Filter by Status
                </label>
                <select name="status"
                        id="status"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all"      {{ ($status ?? 'all') === 'all'      ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending"  {{ ($status ?? '') === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed"{{ ($status ?? '') === 'confirmed'   ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled"{{ ($status ?? '') === 'cancelled'   ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2 pb-0.5">
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg
                               hover:bg-blue-700 transition-colors duration-200 shadow">
                    Search
                </button>
                {{-- Clear resets the form by going back to the base URL with no parameters --}}
                <a href="{{ route('appointments.index') }}"
                   class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg
                          hover:bg-gray-200 transition-colors duration-200">
                    Clear
                </a>
            </div>

        </form>
    </div>

    {{-- Active filter indicator — shows when a filter is currently applied --}}
    @if(!empty($search) || (!empty($status) && $status !== 'all'))
        <div class="mb-4 flex items-center gap-2">
            <span class="text-sm text-gray-500">Showing results for:</span>
            @if(!empty($search))
                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                    Doctor: "{{ $search }}"
                </span>
            @endif
            @if(!empty($status) && $status !== 'all')
                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                    Status: {{ ucfirst($status) }}
                </span>
            @endif
        </div>
    @endif

    {{-- ── APPOINTMENTS TABLE ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($appointments->isEmpty())
            <div class="text-center py-16">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 text-lg font-medium">
                    {{ (!empty($search) || (!empty($status) && $status !== 'all'))
                        ? 'No appointments match your search.'
                        : 'No appointments found.' }}
                </p>
                @if(!empty($search) || (!empty($status) && $status !== 'all'))
                    <a href="{{ route('appointments.index') }}"
                       class="mt-3 inline-block text-sm text-blue-600 hover:underline">
                        Clear filters to see all appointments
                    </a>
                @else
                    <p class="text-gray-400 text-sm mt-1">
                        Click "Book Appointment" to schedule your first visit.
                    </p>
                @endif
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-blue-700">
                    <tr>
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

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                Dr. {{ $appointment->doctor_name }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </td>

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

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                       class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50
                                              rounded hover:bg-blue-100 transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('appointments.edit', $appointment) }}"
                                       class="px-3 py-1 text-xs font-medium text-amber-600 bg-amber-50
                                              rounded hover:bg-amber-100 transition-colors">
                                        Edit
                                    </a>
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