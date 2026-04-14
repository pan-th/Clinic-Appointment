<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Welcome back, {{ auth()->user()->name }}!
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ auth()->user()->isAdmin() ? 'Administrator Dashboard' : 'Patient Dashboard' }}
        </p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">

        @php
            // Get appointment counts for the logged-in user (or all, if admin)
            $query = auth()->user()->isAdmin()
                ? \App\Models\Appointment::query()
                : \App\Models\Appointment::where('user_id', auth()->id());

            $total     = (clone $query)->count();
            $pending   = (clone $query)->where('status', 'pending')->count();
            $confirmed = (clone $query)->where('status', 'confirmed')->count();
        @endphp

        {{-- Total Appointments --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <p class="text-sm font-medium text-gray-500">Total Appointments</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $total }}</p>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <p class="text-sm font-medium text-gray-500">Pending</p>
            <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $pending }}</p>
        </div>

        {{-- Confirmed --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <p class="text-sm font-medium text-gray-500">Confirmed</p>
            <p class="text-3xl font-bold text-green-500 mt-1">{{ $confirmed }}</p>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
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
        </div>
    </div>
</x-app-layout>