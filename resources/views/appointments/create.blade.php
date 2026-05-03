@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h2 class="text-2xl font-semibold mb-6">Book New Appointment</h2>

    <form action="{{ route('appointments.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="client_id">Select Client <span class="text-red-500">*</span></label>
            <select name="client_id" id="client_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline searchable-select">
                <option value="">-- Choose Client --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->first_name }} {{ $client->last_name }} ({{ $client->phone }})
                    </option>
                @endforeach
            </select>
            @error('client_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4 flex space-x-4">
            <div class="w-1/2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="staff_id">Assign Staff <span class="text-red-500">*</span></label>
                <select name="staff_id" id="staff_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline searchable-select">
                    <option value="">-- Choose Staff --</option>
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}" {{ old('staff_id') == $staff->id ? 'selected' : '' }}>
                            {{ $staff->name }}
                        </option>
                    @endforeach
                </select>
                @error('staff_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="w-1/2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="service_type">Service Type <span class="text-red-500">*</span></label>
                <select name="service_type" id="service_type" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline searchable-select">
                    <option value="">-- Choose Service --</option>
                    <option value="Consultation" {{ old('service_type') == 'Consultation' ? 'selected' : '' }}>Consultation</option>
                    <option value="General Checkup" {{ old('service_type') == 'General Checkup' ? 'selected' : '' }}>General Checkup</option>
                    <option value="Follow-up" {{ old('service_type') == 'Follow-up' ? 'selected' : '' }}>Follow-up</option>
                    <option value="Vaccination" {{ old('service_type') == 'Vaccination' ? 'selected' : '' }}>Vaccination</option>
                    <option value="Blood Extraction" {{ old('service_type') == 'Blood Extraction' ? 'selected' : '' }}>Blood Extraction</option>
                    <option value="X-Ray" {{ old('service_type') == 'X-Ray' ? 'selected' : '' }}>X-Ray</option>
                    <option value="Therapy Session" {{ old('service_type') == 'Therapy Session' ? 'selected' : '' }}>Therapy Session</option>
                    <option value="Dental Cleaning" {{ old('service_type') == 'Dental Cleaning' ? 'selected' : '' }}>Dental Cleaning</option>
                    <option value="Eye Checkup" {{ old('service_type') == 'Eye Checkup' ? 'selected' : '' }}>Eye Checkup</option>
                    <option value="Routine Clearance" {{ old('service_type') == 'Routine Clearance' ? 'selected' : '' }}>Routine Clearance</option>
                </select>
                @error('service_type') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4 flex space-x-4">
            <div class="w-1/2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="appointment_date">
                Date <span class="text-red-500">*</span>
            </label>
            <input 
                type="date" 
                name="appointment_date" 
                id="appointment_date" 
                value="{{ old('appointment_date') }}" 
                {{-- ADD THE MIN ATTRIBUTE HERE --}}
                min="{{ date('Y-m-d') }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            >
            @error('appointment_date') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>
            <div class="w-1/2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="appointment_time">Time <span class="text-red-500">*</span></label>
                <input type="time" name="appointment_time" id="appointment_time" value="{{ old('appointment_time') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('appointment_time') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <input type="hidden" name="status" value="Scheduled">

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">Appointment Notes</label>
            <textarea name="notes" id="notes" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Schedule Appointment
            </button>
            <a href="{{ route('appointments.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection