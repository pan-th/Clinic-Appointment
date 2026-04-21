<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * INDEX — Shows only CONFIRMED appointments assigned to this doctor.
     * Filters by two conditions:
     * 1. doctor_name must match the logged-in doctor's name exactly
     * 2. status must be 'confirmed' — pending and cancelled are hidden from doctors
     *
     * Doctors only see appointments after a nurse or admin has confirmed them.
     * This prevents doctors from seeing unverified or cancelled bookings.
     */
    public function index()
    {
        $appointments = Appointment::with('patient')
            ->where('doctor_name', Auth::user()->name)
            ->where('status', 'confirmed')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return view('doctor.index', compact('appointments'));
    }

    /**
     * ADD NOTE — Doctor submits a consultation note for a specific appointment.
     * Only the doctor whose name matches the appointment's doctor_name may add a note.
     * This prevents one doctor from writing notes on another doctor's appointment.
     */
    public function addNote(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_name !== Auth::user()->name) {
            abort(403, 'You can only add notes to your own appointments.');
        }

        $request->validate([
            'notes' => 'required|string|max:2000',
        ]);

        $appointment->update([
            'notes' => $request->notes,
        ]);

        return redirect()->route('doctor.appointments.index')
            ->with('success', 'Consultation note saved successfully.');
    }
}