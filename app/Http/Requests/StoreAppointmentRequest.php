<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // Set this to true. We assume authentication/authorization 
        // is being handled by your routes/middleware.
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'client_id'        => 'required|exists:clients,id',
            'service_type'     => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'staff_id'         => 'required|exists:users,id',
            'appointment_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Check for existing bookings for this staff member at this exact date and time
                    $isDoubleBooked = Appointment::where('staff_id', $this->staff_id)
                        ->where('appointment_date', $this->appointment_date)
                        ->where('appointment_time', $value)
                        ->whereNotIn('status', ['Cancelled', 'No Show'])
                        ->exists();

                    if ($isDoubleBooked) {
                        $fail('This staff member is already booked for this specific date and time.');
                    }
                },
            ],
            'status'           => 'required|string|in:Scheduled,Confirmed,Completed,Cancelled,No Show',
            'notes'            => 'nullable|string',
        ];
    }
}
