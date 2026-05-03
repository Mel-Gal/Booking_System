<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Http\Requests\StoreAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['client', 'staff']);

        if (Auth::user()->role !== 'Admin' && Auth::user()->role !== 'Receptionist') {
            $query->where('staff_id', Auth::id());
        }

        if ($request->has('upcoming')) {
            $query->where('appointment_date', '>=', now()->toDateString());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date', 'asc')
                             ->orderBy('appointment_time', 'asc')
                             ->paginate(15);
                               
        $appointments->appends($request->all());

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $clients = Client::orderBy('first_name')->get();
        $staffMembers = User::where('role', 'Staff')->get();
        return view('appointments.create', compact('clients', 'staffMembers'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $validatedData = $request->validated();

        $isDoubleBooked = Appointment::where('staff_id', $validatedData['staff_id'])
            ->where('appointment_date', $validatedData['appointment_date'])
            ->where('appointment_time', $validatedData['appointment_time'])
            ->where('status', '!=', 'Cancelled')
            ->exists();

        if ($isDoubleBooked) {
            return back()->withInput()->withErrors(['appointment_time' => 'This staff member is already booked!']);
        }
        
        $validatedData['created_by'] = Auth::id();
        
        /** * FIX 1: Initialize the lifecycle here.
         * When an appointment is created, it's officially "Scheduled."
         */
        $validatedData['status'] = 'Scheduled';
        $validatedData['scheduled_at'] = now();

        Appointment::create($validatedData);

        return redirect()->route('appointments.index')
                         ->with('success', 'Appointment successfully scheduled.');
    }

    public function edit(Appointment $appointment)
    {
        $clients = Client::orderBy('first_name')->get();
        $staffMembers = User::where('role', 'Staff')->get();
        return view('appointments.edit', compact('appointment', 'clients', 'staffMembers'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validatedData = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'staff_id'         => 'required|exists:users,id',
            'service_type'     => 'required|string|max:255',
        ]);

        $isDoubleBooked = Appointment::where('staff_id', $request->staff_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('id', '!=', $appointment->id) 
            ->where('status', '!=', 'Cancelled')
            ->exists();

        if ($isDoubleBooked) {
            return back()->withInput()->withErrors(['appointment_time' => 'This staff member is already booked!']);
        }

        $appointment->update($validatedData);

        return redirect()->route('appointments.index')
                         ->with('success', 'Appointment rescheduled successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete(); 
        return redirect()->route('appointments.index')->with('success', 'Appointment removed.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:Scheduled,Confirmed,Completed,Cancelled,No Show'
        ]);

        $data = ['status' => $request->status];
        
        $statusMap = [
            'Scheduled' => 'scheduled_at',
            'Confirmed' => 'confirmed_at',
            'Completed' => 'completed_at',
            'Cancelled' => 'cancelled_at',
            'No Show' => 'no_show_at',
        ];

        // Update the timestamp for the new status
        if (isset($statusMap[$request->status])) {
            $data[$statusMap[$request->status]] = now();
        }

        /**
         * FIX 2: We use update() on the model instance. 
         * This only changes the 'status' and the ONE timestamp column, 
         * leaving the other timestamps (like scheduled_at) untouched.
         */
        $appointment->update($data);

        if ($request->status === 'Cancelled') {
            $appointment->delete(); // Soft delete
            return redirect()->route('appointments.index')
                             ->with('success', 'Appointment cancelled and archived.');
        }
        if ($request->status === 'No Show') {
            $appointment->delete(); // Soft delete
            return redirect()->route('appointments.index')
                             ->with('success', 'Appointment is no show and archived.');
        }

        return redirect()->back()
                         ->with('success', 'Status updated to ' . $request->status . '.');
    }
}