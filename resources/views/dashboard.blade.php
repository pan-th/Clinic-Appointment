<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- ── PAGE HEADER ── --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Welcome back, {{ auth()->user()->name }}!
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            @if(auth()->user()->isAdmin())
                Administrator Dashboard — full system overview.
            @elseif(auth()->user()->isNurse())
                Nurse Dashboard — manage the appointment queue.
            @elseif(auth()->user()->isDoctor())
                Doctor Dashboard — view your scheduled appointments.
            @else
                Patient Dashboard — manage your appointments.
            @endif
        </p>
    </div>

    @php
        // Build the base query depending on the logged-in user's role.
        // Admin sees all records. Everyone else sees only their own.
        $query = auth()->user()->isAdmin()
            ? \App\Models\Appointment::query()
            : \App\Models\Appointment::where('user_id', auth()->id());

        // Count each status from the same base query using clone
        // clone is used so each count is a fresh copy and doesn't affect the others
        $total     = (clone $query)->count();
        $pending   = (clone $query)->where('status', 'pending')->count();
        $confirmed = (clone $query)->where('status', 'confirmed')->count();
        $cancelled = (clone $query)->where('status', 'cancelled')->count();

        // Get the 5 most recent appointments for the Recent Appointments table
        $recent = (clone $query)->with('patient')->latest()->take(5)->get();
    @endphp

    {{-- ── STAT CARDS ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Total Appointments --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Appointments</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $total }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $pending }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Confirmed --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Confirmed</p>
                    <p class="text-3xl font-bold text-green-500 mt-1">{{ $confirmed }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Cancelled — NEW fourth card --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Cancelled</p>
                    <p class="text-3xl font-bold text-red-500 mt-1">{{ $cancelled }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ── QUICK ACTIONS ── --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">

            @if(auth()->user()->isAdmin() || auth()->user()->isPatient())
                <a href="{{ route('appointments.create') }}"
                   class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg
                          hover:bg-blue-700 transition-colors">
                    + Book New Appointment
                </a>
                <a href="{{ route('appointments.index') }}"
                   class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg
                          hover:bg-gray-200 transition-colors">
                    View All Appointments
                </a>
            @endif

            @if(auth()->user()->isNurse())
                <a href="{{ route('nurse.appointments.index') }}"
                   class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg
                          hover:bg-blue-700 transition-colors">
                    Open Appointment Queue
                </a>
            @endif

            @if(auth()->user()->isDoctor())
                <a href="{{ route('doctor.appointments.index') }}"
                   class="px-5 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg
                          hover:bg-green-700 transition-colors">
                    View My Schedule
                </a>
            @endif

            <a href="{{ route('profile.edit') }}"
               class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg
                      hover:bg-gray-200 transition-colors">
                Edit Profile
            </a>
        </div>
    </div>

    {{-- ── RECENT APPOINTMENTS ── --}}
    {{-- Shows the 5 most recently created appointments --}}
    {{-- Admin sees from all patients. Patient sees only their own. --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Appointments</h2>

        @if($recent->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-400 text-sm">No appointments yet.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg border border-gray-100">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-blue-700">
                        <tr>
                            @if(auth()->user()->isAdmin())
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                                    Patient
                                </th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                                Doctor
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-blue-100 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-blue-100 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @foreach($recent as $appointment)
                            <tr class="hover:bg-gray-50 transition-colors">

                                @if(auth()->user()->isAdmin())
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $appointment->patient->name }}
                                    </td>
                                @endif

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    Dr. {{ $appointment->doctor_name }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    @php
                                        $badgeClass = match($appointment->status) {
                                            'confirmed' => 'bg-green-100 text-green-700 border border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-700 border border-red-200',
                                            default     => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                       class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                        View →
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Link to full list --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isPatient())
                <div class="mt-3 text-right">
                    <a href="{{ route('appointments.index') }}"
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                        View all appointments →
                    </a>
                </div>
            @endif
        @endif
    </div>

</x-app-layout>