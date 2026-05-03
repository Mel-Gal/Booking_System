<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Appointment;
use App\Http\Requests\StoreServiceRecordRequest;
use Illuminate\Http\Request;

class ServiceRecordController extends Controller
{
public function index(Request $request)
{
    $query = ServiceRecord::with(['appointment.client', 'appointment.staff']);

    if ($request->filled('search')) {
        $searchTerm = $request->search;
        
        $query->whereHas('appointment.client', function($q) use ($searchTerm) {
            $q->where('first_name', 'like', "%{$searchTerm}%")
              ->orWhere('last_name', 'like', "%{$searchTerm}%");
        })->orWhereHas('appointment.staff', function($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%");
        });
    }

    // Paginate and preserve search parameters in links
    $serviceRecords = $query->orderBy('created_at', 'desc')->paginate(10);
    $serviceRecords->appends($request->all());

    return view('service-records.index', compact('serviceRecords'));
}

public function edit(ServiceRecord $serviceRecord)
{
    // Fetch appointments so they can change the linked appointment if needed
    $appointments = \App\Models\Appointment::with('client', 'staff')->get(); 
    return view('service-records.edit', compact('serviceRecord', 'appointments'));
}

public function update(Request $request, ServiceRecord $serviceRecord)
{
    $validated = $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'description'    => 'required|string',
        'remarks' => 'nullable|string',
    ]);

    $serviceRecord->update($validated);

    return redirect()->route('service-records.index')->with('success', 'Service Record updated successfully!');
}
public function show(ServiceRecord $serviceRecord)
    {
        // This will load the resources/views/service-records/show.blade.php file we created earlier
        return view('service-records.show', compact('serviceRecord'));
    }

public function destroy(ServiceRecord $serviceRecord)
{
    $serviceRecord->delete();
    return redirect()->route('service-records.index')->with('success', 'Service record deleted successfully.');
}

    public function create(Request $request)
{
    $appointments = \App\Models\Appointment::with('client', 'staff')->get();
    $selectedAppointmentId = $request->query('appointment_id'); // Grabs the ID from the URL
    
    return view('service-records.create', compact('appointments', 'selectedAppointmentId'));
}

   public function store(StoreServiceRecordRequest $request)
{
    $validatedData = $request->validated();

    // Find the appointment
    $appointment = Appointment::findOrFail($validatedData['appointment_id']);

    // Create the Service Record
    ServiceRecord::create([
        'appointment_id' => $appointment->id,
        'client_id'      => $appointment->client_id,
        'staff_id'       => $appointment->staff_id,
        'service_date'   => $validatedData['service_date'],
        'description'    => $validatedData['description'],
        'remarks'        => $validatedData['remarks'] ?? null,
    ]);

    // NEW: Automatically remove the appointment from the active list
    $appointment->delete();

    return redirect()->route('service-records.index')
                     ->with('success', 'Service record saved and appointment removed from active list.');
}
}