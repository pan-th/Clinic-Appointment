<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * INDEX — Shows only appointments assigned to this doctor.
     * Matches doctor_name column against the logged-in user's name.
     * Ordered by appointment date ascending so the doctor sees their next patient first.
     */
    public function index()
    {
        $appointments = Appointment::with('patient')
            ->where('doctor_name', Auth::user()->name)
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
        // Verify this appointment actually belongs to the logged-in doctor
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