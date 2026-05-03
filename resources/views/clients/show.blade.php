@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Client Profile: {{ $client->first_name }} {{ $client->last_name }}</h2>
        <a href="{{ route('clients.index') }}" class="text-blue-500 hover:underline">&larr; Back to List</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Contact Details</h3>
            <p class="mb-2"><strong>Email:</strong> {{ $client->email ?? 'N/A' }}</p>
            <p class="mb-2"><strong>Phone:</strong> {{ $client->phone }}</p>
            <p class="mb-2"><strong>Address:</strong><br> {{ $client->address ?? 'No address provided' }}</p>
            
            @if(auth()->user()->role !== 'Staff')
                <div class="mt-6">
                    <a href="{{ route('clients.edit', $client) }}" class="bg-yellow-500 text-white px-4 py-2 rounded text-sm block text-center">Edit Details</a>
                </div>
            @endif
        </div>

        <div class="md:col-span-2 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Appointment History</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b">
                            <th class="pb-3 px-2">Date & Time</th>
                            <th class="pb-3 px-2">Staff</th>
                            <th class="pb-3 px-2">Status</th>
                            <th class="pb-3 px-2">Status History</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        {{-- Use $appointments here to ensure trashed/deleted ones show up --}}
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="py-4 px-2 text-sm">
                                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                </td>
                                <td class="py-4 px-2 text-sm text-gray-700">
                                    {{ $appointment->staff?->name ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-2">
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full 
                                        {{ $appointment->status === 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $appointment->status === 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $appointment->status === 'No Show' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ in_array($appointment->status, ['Scheduled', 'Confirmed']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-2 text-[10px] text-gray-500 leading-tight">
                                    @if($appointment->scheduled_at) 
                                        <div><span class="font-semibold">Booked:</span> {{ $appointment->scheduled_at->format('M d, h:i A') }}</div> 
                                    @endif
                                    @if($appointment->confirmed_at) 
                                        <div><span class="font-semibold text-indigo-600">Confirmed:</span> {{ $appointment->confirmed_at->format('M d, h:i A') }}</div> 
                                    @endif
                                    @if($appointment->completed_at) 
                                        <div><span class="font-semibold text-green-600">Finished:</span> {{ $appointment->completed_at->format('M d, h:i A') }}</div> 
                                    @endif
                                    @if($appointment->cancelled_at) 
                                        <div class="text-red-500"><span class="font-semibold">Cancelled:</span> {{ $appointment->cancelled_at->format('M d, h:i A') }}</div> 
                                    @endif
                                     @if($appointment->no_show_at) 
                                        <div class="text-red-500"><span class="font-semibold">No Show:</span> {{ $appointment->no_show_at->format('M d, h:i A') }}</div> 
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">No appointments found for this client.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection