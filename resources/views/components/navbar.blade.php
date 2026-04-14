
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

            {{-- ── NAVIGATION LINKS (Center / Right) ── --}}
            <div class="flex items-center space-x-1">

                {{-- Home Link --}}
                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('home')
                              ? 'bg-white text-blue-700 font-semibold'
                              : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                    Home
                </a>

                {{-- Show these links only if the user is LOGGED IN --}}
                @auth
                    {{-- Appointments List --}}
                    <a href="{{ route('appointments.index') }}"
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                              {{ request()->routeIs('appointments.index')
                                  ? 'bg-white text-blue-700 font-semibold'
                                  : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                        Appointments
                    </a>

                    {{-- Add Appointment --}}
                    <a href="{{ route('appointments.create') }}"
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                              {{ request()->routeIs('appointments.create')
                                  ? 'bg-white text-blue-700 font-semibold'
                                  : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                        + Add Appointment
                    </a>

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                              {{ request()->routeIs('dashboard')
                                  ? 'bg-white text-blue-700 font-semibold'
                                  : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                        Dashboard
                    </a>

                    {{-- Show "Admin Panel" badge only for admins --}}
                    @if(auth()->user()->isAdmin())
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-yellow-400 text-yellow-900">
                            ADMIN
                        </span>
                    @endif

                    {{-- Logout Button --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="ml-2 px-4 py-2 rounded-md text-sm font-medium bg-red-500
                                       text-white hover:bg-red-600 transition-colors duration-200">
                            Logout
                        </button>
                    </form>
                @endauth

                {{-- Show these links only if the user is NOT logged in --}}
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