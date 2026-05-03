@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8 py-8">
    <h2 class="text-2xl font-semibold leading-tight mb-6">Reschedule Appointment</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('appointments.update', $appointment) }}" method="POST">
            @csrf
            @method('PUT') 
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Assigned Staff</label>
                <select name="staff_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}" {{ $appointment->staff_id == $staff->id ? 'selected' : '' }}>
                            {{ $staff->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex mb-4 space-x-4">
                <div class="w-1/2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                    {{-- Added min attribute to prevent past date selection --}}
                    <input type="date" name="appointment_date" value="{{ $appointment->appointment_date }}" min="{{ date('Y-m-d') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Time</label>
                    <input type="time" name="appointment_time" value="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Service Type</label>
                {{-- Changed from text input to select dropdown --}}
                <select name="service_type" id="service_type" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="Consultation" {{ $appointment->service_type == 'Consultation' ? 'selected' : '' }}>Consultation</option>
                    <option value="General Checkup" {{ $appointment->service_type == 'General Checkup' ? 'selected' : '' }}>General Checkup</option>
                    <option value="Follow-up" {{ $appointment->service_type == 'Follow-up' ? 'selected' : '' }}>Follow-up</option>
                    <option value="Vaccination" {{ $appointment->service_type == 'Vaccination' ? 'selected' : '' }}>Vaccination</option>
                    <option value="Blood Extraction" {{ $appointment->service_type == 'Blood Extraction' ? 'selected' : '' }}>Blood Extraction</option>
                    <option value="X-Ray" {{ $appointment->service_type == 'X-Ray' ? 'selected' : '' }}>X-Ray</option>
                    <option value="Therapy Session" {{ $appointment->service_type == 'Therapy Session' ? 'selected' : '' }}>Therapy Session</option>
                    <option value="Dental Cleaning" {{ $appointment->service_type == 'Dental Cleaning' ? 'selected' : '' }}>Dental Cleaning</option>
                    <option value="Eye Checkup" {{ $appointment->service_type == 'Eye Checkup' ? 'selected' : '' }}>Eye Checkup</option>
                    <option value="Routine Clearance" {{ $appointment->service_type == 'Routine Clearance' ? 'selected' : '' }}>Routine Clearance</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save Changes
                </button>
                <a href="{{ route('appointments.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection