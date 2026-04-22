<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Records which nurse performed the action and exactly when it was done.
     * These three fields are always updated together as one atomic operation.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $appointment->update([
            'status'               => $request->status,
            'actioned_by_nurse_id' => Auth::id(),  // Track which nurse did this
            'actioned_at'          => now(),        // Track exactly when they did it
        ]);

        return redirect()->route('nurse.appointments.index')
            ->with('success', 'Appointment status updated to ' . ucfirst($request->status) . '.');
    }
}