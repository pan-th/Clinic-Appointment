<x-app-layout>
    {{-- Hero Section --}}
    <div class="text-center py-20">

        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-6">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>

        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            Welcome to <span class="text-blue-600">ClinicCare</span>
        </h1>
        <p class="text-gray-500 text-lg max-w-xl mx-auto mb-8">
            Book your clinic appointments online — fast, easy, and secure.
            Your health is our priority.
        </p>

        <div class="flex items-center justify-center gap-4">
            @guest
                <a href="{{ route('register') }}"
                   class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg
                          hover:bg-blue-700 transition-colors duration-200 shadow-md">
                    Get Started
                </a>
                <a href="{{ route('login') }}"
                   class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg border
                          border-blue-300 hover:bg-blue-50 transition-colors duration-200">
                    Sign In
                </a>
            @endguest

            @auth
                <a href="{{ route('appointments.create') }}"
                   class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg
                          hover:bg-blue-700 transition-colors duration-200 shadow-md">
                    Book an Appointment
                </a>
                <a href="{{ route('appointments.index') }}"
                   class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg border
                          border-blue-300 hover:bg-blue-50 transition-colors duration-200">
                    View My Appointments
                </a>
            @endauth
        </div>
    </div>

    {{-- Feature Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Easy Scheduling</h3>
            <p class="text-sm text-gray-500">Book appointments with your preferred doctor in just a few clicks.</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Secure & Private</h3>
            <p class="text-sm text-gray-500">Your medical information is protected and only visible to you.</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-1">Real-Time Updates</h3>
            <p class="text-sm text-gray-500">Get instant status updates when your appointment is confirmed.</p>
        </div>
    </div>
</x-app-layout>