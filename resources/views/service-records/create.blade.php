@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h2 class="text-2xl font-semibold mb-6">Log Service Record</h2>

    <form action="{{ route('service-records.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="appointment_id">Select Completed Appointment <span class="text-red-500">*</span></label>
            <select name="appointment_id" id="appointment_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline searchable-select">
                <option value="">-- Choose Appointment --</option>
                @foreach($appointments as $appointment)
                    <option value="{{ $appointment->id }}" 
                            data-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}"
                            {{ (old('appointment_id') ?? $selectedAppointmentId) == $appointment->id ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} - 
                        {{ $appointment->client?->first_name ?? 'Unknown' }} {{ $appointment->client?->last_name ?? 'Client' }}
                        ({{ $appointment->service_type }}) with {{ $appointment->staff?->name ?? 'Unknown Staff' }}
                    </option>
                @endforeach
            </select>
            @error('appointment_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            @if($appointments->isEmpty())
                <p class="text-orange-500 text-xs mt-2">No completed appointments are waiting for a service record.</p>
            @endif
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="service_date">Date of Service <span class="text-red-500">*</span></label>
            <input type="date" name="service_date" id="service_date" value="{{ old('service_date', \Carbon\Carbon::today()->toDateString()) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('service_date') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Service Notes / Description <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="5" placeholder="Detail the service provided, observations, etc." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="remarks">Outcome / Remarks</label>
            <textarea name="remarks" id="remarks" rows="3" placeholder="Any follow-up needed or final outcomes" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('remarks') }}</textarea>
            @error('remarks') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Save Record
            </button>
            <a href="{{ route('service-records.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const appointmentSelect = document.getElementById('appointment_id');
        const serviceDateInput = document.getElementById('service_date');

        const updateDate = () => {
            const selectedOption = appointmentSelect.options[appointmentSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.date) {
                serviceDateInput.value = selectedOption.dataset.date;
            }
        };

        // Update the date when the user changes the dropdown
        appointmentSelect.addEventListener('change', updateDate);

        // Run once immediately on page load (handles pre-selected appointments via the "+ Add Record" button)
        setTimeout(updateDate, 100); 
    });
</script>
@endsection