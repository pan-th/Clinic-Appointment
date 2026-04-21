<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * INDEX — Lists appointments with optional search and status filters.
     * Admin sees all records. Patient sees only their own.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        if (Auth::user()->isAdmin()) {
            $query = Appointment::with('patient');
        } else {
            $query = Appointment::with('patient')
                ->where('user_id', Auth::id());
        }

        if (!empty($search)) {
            $query->where('doctor_name', 'like', '%' . $search . '%');
        }

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        $appointments = $query->latest()->get();

        return view('appointments.index', compact('appointments', 'search', 'status'));
    }

    /**
     * CREATE — Shows the booking form.
     * Fetches all users with role = 'doctor' to populate the dropdown.
     * Ordered alphabetically by name.
     */
    public function create()
    {
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();

        return view('appointments.create', compact('doctors'));
    }

    /**
     * STORE — Validates and saves a new appointment.
     * doctor_name is now the selected doctor's name string from the dropdown.
     * The validation checks against the actual list of doctor names in the database.
     */
    public function store(Request $request)
    {
        // Build the list of valid doctor names for validation
        $validDoctorNames = User::where('role', 'doctor')
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        $request->validate([
            'doctor_name'      => 'required|string|in:' . implode(',', $validDoctorNames),
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
     * SHOW — Displays full details of one appointment.
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
     * EDIT — Shows the pre-filled edit form.
     * Fetches all users with role = 'doctor' to populate the dropdown.
     * Passes both $appointment and $doctors to the view.
     */
    public function edit(Appointment $appointment)
    {
        if (!Auth::user()->isAdmin() && $appointment->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to edit this appointment.');
        }

        $doctors = User::where('role', 'doctor')->orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'doctors'));
    }

    /**
     * UPDATE — Saves changes to an existing appointment.
     * doctor_name is validated against real doctor names in the database.
     * Only admins may update the status field.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!Auth::user()->isAdmin() && $appointment->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to update this appointment.');
        }

        // Build the list of valid doctor names for validation
        $validDoctorNames = User::where('role', 'doctor')
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        $rules = [
            'doctor_name'      => 'required|string|in:' . implode(',', $validDoctorNames),
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