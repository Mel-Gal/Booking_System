<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to true assuming middleware handles authentication
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'nullable|email|max:255|unique:clients,email',
            'phone'      => 'required|string|max:20',
            'address'    => 'nullable|string|max:500',
            'notes'      => 'nullable|string',
        ];
    }
}
