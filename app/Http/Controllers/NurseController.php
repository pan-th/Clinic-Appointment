<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class NurseController extends Controller
{
    /**
     * INDEX — Shows all appointments in the system to the nurse.
     * Includes the patient's name and current status.
     * Ordered by appointment date ascending so the nurse sees the next upcoming first.
     */
    public function index()
    {
        $appointments = Appointment::with('patient')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return view('nurse.index', compact('appointments'));
    }

    /**
     * UPDATE STATUS — Nurse can change status to 'confirmed' or 'cancelled' only.
     * 'pending' is not listed here because pending is the default on creation.
     * Only valid values are accepted — anything else is rejected with a 422 error.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return redirect()->route('nurse.appointments.index')
            ->with('success', 'Appointment status updated to ' . ucfirst($request->status) . '.');
    }
}