<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    {{-- ── PAGE TITLE ── --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your account information and password.</p>
    </div>

    <div class="max-w-2xl space-y-6">

        {{-- ── SECTION 1: Update Profile Information ── --}}
        {{-- This section contains the Breeze form for name and email --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- ── SECTION 2: Update Password ── --}}
        {{-- This section contains the Breeze form for changing password --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- ── SECTION 3: Delete Account ── --}}
        {{-- This section contains the Breeze form for deleting the account --}}
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>