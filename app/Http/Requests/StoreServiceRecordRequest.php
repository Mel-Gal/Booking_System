<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRecordRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'appointment_id' => 'required|exists:appointments,id|unique:service_records,appointment_id',
            'service_date'   => 'required|date',
            'description'    => 'required|string',
            'remarks'        => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'appointment_id.unique' => 'A service record already exists for this appointment.',
        ];
    }
}
