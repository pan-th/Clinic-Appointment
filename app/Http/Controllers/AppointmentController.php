<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * INDEX — Shows the list of all appointments.
     * Admins see ALL appointments from all patients.
     * Patients only see THEIR OWN appointments.
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            // Admin: get all appointments, and also load the patient's name
            $appointments = Appointment::with('patient')->latest()->get();
        } else {
            // Patient: only get appointments that belong to the logged-in user
            $appointments = Appointment::where('user_id', Auth::id())->latest()->get();
        }

        return view('appointments.index', compact('appointments'));
    }

    /**
     * CREATE — Shows the form to book a new appointment.
     * Both admins and patients can see this form.
     */
    public function create()
    {
        return view('appointments.create');
    }

    /**
     * STORE — Saves the new appointment to the database.
     * Validates the form inputs before saving.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_name'      => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:1000',
        ]);

        Appointment::create([
            'user_id'          => Auth::id(),        
            'doctor_name'      => $request->doctor_name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason'           => $request->reason,
            'status'           => 'pending',         
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Your appointment has been booked successfully!');
    }

    /**
     * SHOW — Displays the details of one appointment.
     * Patients can only view their own. Admins can view any.
     */
    public function show(Appointment $appointment)
    {
        if (!Auth::user()->isAdmin() && $appointment->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to view this appointment.');
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * EDIT — Shows the form to edit an existing appointment.
     * Patients can only edit their own. Admins can edit any.
     */
    public function edit(Appointment $appointment)
    {
        if (!Auth::user()->isAdmin() && $appointment->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to edit this appointment.');
        }

        return view('appointments.edit', compact('appointment'));
    }

    /**
     * UPDATE — Saves the edited appointment data to the database.
     * Only admins can change the status (pending/confirmed/cancelled).
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!Auth::user()->isAdmin() && $appointment->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to update this appointment.');
        }

        $rules = [
            'doctor_name'      => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:1000',
        ];

        if (Auth::user()->isAdmin()) {
            $rules['status'] = 'required|in:pending,confirmed,cancelled';
        }

        $request->validate($rules);

        $data = [
            'doctor_name'      => $request->doctor_name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason'           => $request->reason,
        ];

        if (Auth::user()->isAdmin()) {
            $data['status'] = $request->status;
        }

        $appointment->update($data);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * DESTROY — Deletes an appointment permanently.
     * Patients can only delete their own. Admins can delete any.
     */
    public function destroy(Appointment $appointment)
    {
        if (!Auth::user()->isAdmin() && $appointment->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to delete this appointment.');
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }
}