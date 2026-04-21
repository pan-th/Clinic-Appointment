{{-- ============================================================
     CLINIC NAVBAR COMPONENT
     Usage: Add <x-navbar /> to any Blade layout file.
     Automatically shows/hides links based on login status and role.
     ============================================================ --}}

<nav class="bg-blue-700 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- ── CLINIC NAME / LOGO (Left Side) ── --}}
            <div class="flex items-center space-x-2">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <a href="{{ route('home') }}" class="text-white font-bold text-xl tracking-wide">
                    ClinicCare
                </a>
            </div>

            {{-- ── NAVIGATION LINKS (Right Side) ── --}}
            <div class="flex items-center space-x-1">

                {{-- Home — visible to everyone --}}
                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('home')
                              ? 'bg-white text-blue-700 font-semibold'
                              : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                    Home
                </a>

                {{-- ── LOGGED-IN LINKS ── --}}
                @auth

                    {{-- Dashboard — visible to all logged-in roles --}}
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                              {{ request()->routeIs('dashboard')
                                  ? 'bg-white text-blue-700 font-semibold'
                                  : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                        Dashboard
                    </a>

                    {{-- Appointments — visible to Admin and Patient only --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isPatient())
                        <a href="{{ route('appointments.index') }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('appointments.*')
                                      ? 'bg-white text-blue-700 font-semibold'
                                      : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                            Appointments
                        </a>
                    @endif

                    {{-- Add Appointment — visible to Patients only --}}
                    @if(auth()->user()->isPatient())
                        <a href="{{ route('appointments.create') }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('appointments.create')
                                      ? 'bg-white text-blue-700 font-semibold'
                                      : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                            + Book Appointment
                        </a>
                    @endif

                    {{-- Nurse Queue — visible to Nurses only --}}
                    @if(auth()->user()->isNurse())
                        <a href="{{ route('nurse.appointments.index') }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('nurse.*')
                                      ? 'bg-white text-blue-700 font-semibold'
                                      : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                            Appointment Queue
                        </a>
                    @endif

                    {{-- Doctor Schedule — visible to Doctors only --}}
                    @if(auth()->user()->isDoctor())
                        <a href="{{ route('doctor.appointments.index') }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                                  {{ request()->routeIs('doctor.*')
                                      ? 'bg-white text-blue-700 font-semibold'
                                      : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                            My Schedule
                        </a>
                    @endif

                    {{-- ── ROLE BADGE ── --}}
                    {{-- Shows a colored badge indicating the logged-in user's role --}}
                    @if(auth()->user()->isAdmin())
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-yellow-400 text-yellow-900">
                            ADMIN
                        </span>
                    @elseif(auth()->user()->isNurse())
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-400 text-blue-900">
                            NURSE
                        </span>
                    @elseif(auth()->user()->isDoctor())
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-400 text-green-900">
                            DOCTOR
                        </span>
                    @endif

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="ml-2 px-4 py-2 rounded-md text-sm font-medium bg-red-500
                                       text-white hover:bg-red-600 transition-colors duration-200">
                            Logout
                        </button>
                    </form>

                @endauth

                {{-- ── GUEST LINKS (not logged in) ── --}}
                @guest
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                              {{ request()->routeIs('login')
                                  ? 'bg-white text-blue-700 font-semibold'
                                  : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-md text-sm font-medium bg-white text-blue-700
                              hover:bg-blue-50 transition-colors duration-200">
                        Register
                    </a>
                @endguest

            </div>
        </div>
    </div>
</nav>