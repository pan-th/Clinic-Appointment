<x-app-layout>
    <x-slot name="title">Edit Appointment</x-slot>

    {{-- ── PAGE HEADER ── --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Appointment</h1>
        <p class="text-sm text-gray-500 mt-1">Update the appointment details below.</p>
    </div>

    {{-- ── FORM CARD ── --}}
    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-200 p-8">

        <form method="POST" action="{{ route('appointments.update', $appointment) }}">
            @csrf
            @method('PUT')

            {{-- ── Doctor Name Dropdown ── --}}
            <div class="mb-5">
                <label for="doctor_name" class="block text-sm font-semibold text-gray-700 mb-1">
                    Select Doctor <span class="text-red-500">*</span>
                </label>

                @if($doctors->isEmpty())
                    {{-- No doctor accounts exist yet --}}
                    <select name="doctor_name"
                            id="doctor_name"
                            disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                                   bg-gray-100 text-gray-400 cursor-not-allowed
                                   focus:outline-none">
                        <option>No doctors available — ask admin to add a doctor account</option>
                    </select>
                    <p class="mt-1 text-xs text-amber-600">
                        ⚠ No doctors are registered yet. An admin must create a doctor account first.
                    </p>
                @else
                    <select name="doctor_name"
                            id="doctor_name"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   @error('doctor_name') border-red-400 bg-red-50 @enderror">

                        <option value="" disabled>— Select a doctor —</option>

                        @foreach($doctors as $doctor)
                            {{-- old() takes priority for re-population after validation errors --}}
                            {{-- Falls back to the appointment's currently saved doctor_name --}}
                            <option value="{{ $doctor->name }}"
                                {{ old('doctor_name', $appointment->doctor_name) === $doctor->name ? 'selected' : '' }}>
                                Dr. {{ $doctor->name }}
                            </option>
                        @endforeach

                    </select>
                @endif

                @error('doctor_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Date and Time ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

                <div>
                    <label for="appointment_date" class="block text-sm font-semibold text-gray-700 mb-1">
                        Appointment Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           name="appointment_date"
                           id="appointment_date"
                           value="{{ old('appointment_date', $appointment->appointment_date) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  @error('appointment_date') border-red-400 bg-red-50 @enderror">
                    @error('appointment_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="appointment_time" class="block text-sm font-semibold text-gray-700 mb-1">
                        Appointment Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time"
                           name="appointment_time"
                           id="appointment_time"
                           value="{{ old('appointment_time', $appointment->appointment_time) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  @error('appointment_time') border-red-400 bg-red-50 @enderror">
                    @error('appointment_time')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ── Reason ── --}}
            <div class="mb-5">
                <label for="reason" class="block text-sm font-semibold text-gray-700 mb-1">
                    Reason for Visit <span class="text-red-500">*</span>
                </label>
                <textarea name="reason"
                          id="reason"
                          rows="4"
                          placeholder="Describe your symptoms or reason for visiting..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                 @error('reason') border-red-400 bg-red-50 @enderror">{{ old('reason', $appointment->reason) }}</textarea>
                @error('reason')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Status (Admin Only) ── --}}
            @if(auth()->user()->isAdmin())
                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">
                        Appointment Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   @error('status') border-red-400 bg-red-50 @enderror">
                        <option value="pending"   {{ old('status', $appointment->status) === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $appointment->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ old('status', $appointment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Only admins can change the appointment status.</p>
                </div>
            @endif

            {{-- ── Form Buttons ── --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg
                               hover:bg-blue-700 transition-colors duration-200 shadow">
                    Save Changes
                </button>
                <a href="{{ route('appointments.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg
                          hover:bg-gray-200 transition-colors duration-200">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</x-app-layout>