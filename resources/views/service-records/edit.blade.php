@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8 py-8">
    <div class="max-w-lg mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold leading-tight">Edit Service Record</h2>
        </div>

        <form action="{{ route('service-records.update', $serviceRecord) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="appointment_id">
                    Linked Appointment
                </label>
                <select name="appointment_id" id="appointment_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="" disabled>-- Choose Appointment --</option>
                    @foreach($appointments as $appointment)
                        <option value="{{ $appointment->id }}" {{ (old('appointment_id', $serviceRecord->appointment_id) == $appointment->id) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} - 
                            {{ $appointment->client?->first_name ?? 'Unknown' }} {{ $appointment->client?->last_name ?? 'Client' }} 
                            ({{ $appointment->service_type }})
                        </option>
                    @endforeach
                </select>
                @error('appointment_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
        <textarea name="description" id="description" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description', $serviceRecord->description) }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
        </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="remarks">
                    Remarks / Notes
                </label>
                <textarea name="remarks" id="remarks" rows="5" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Add any service notes here...">{{ old('remarks', $serviceRecord->remarks) }}</textarea>
                @error('remarks')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-8">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Record
                </button>
                <a href="{{ route('service-records.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection