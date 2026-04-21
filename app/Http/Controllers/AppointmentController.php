<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * INDEX — Lists appointments with optional search and status filters.
     * Admin sees all records. Patient sees only their own.
     * Filters are read from GET query parameters: ?search=Santos&status=confirmed
     */
    public function index(Request $request)
    {
        // Read the two optional filter values from the URL query string
        $search = $request->input('search');
        $status = $request->input('status');

        if (Auth::user()->isAdmin()) {
            // Admin: start with ALL appointments, eager-load the patient name
            $query = Appointment::with('patient');
        } else {
            // Patient: start with only their own appointments
            $query = Appointment::with('patient')
                ->where('user_id', Auth::id());
        }

        // Apply doctor name search filter if a search term was typed
        if (!empty($search)) {
            $query->where('doctor_name', 'like', '%' . $search . '%');
        }

        // Apply status filter only if a real status was selected (not "all")
        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        // Order by most recently created first
        $appointments = $query->latest()->get();

        // Pass $search and $status back to the view so the form retains its values
        return view('appointments.index', compact('appointments', 'search', 'status'));
    }

    /**
     * CREATE — Shows the form to book a new appointment.
     */
    public function create()
    {
        return view('appointments.create');
    }

    /**
     * STORE — Validates and saves a new appointment to the database.
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
     * SHOW — Displays the full details of one appointment.
     * Patients may only view their own. Admins may view any.
     */
    public function show(Appointment $appointment)
    {
        if (!Auth::user()->isAdmin() && $appointment->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to view this appointment.');
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * EDIT — Shows the pre-filled edit form for an existing appointment.
     * Patients may only edit their own. Admins may edit any.
     */
    public function edit(Appointment $appointment)
    {
        if (!Auth::user()->isAdmin() && $appointment->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to edit this appointment.');
        }

        return view('appointments.edit', compact('appointment'));
    }

    /**
     * UPDATE — Saves changes to an existing appointment.
     * Only admins may update the status field.
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
     * DESTROY — Permanently deletes an appointment.
     * Patients may only delete their own. Admins may delete any.
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