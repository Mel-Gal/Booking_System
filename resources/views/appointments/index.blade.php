@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold leading-tight">Appointments</h2>
        @if(auth()->user()->hasRole('Admin', 'Receptionist'))
            <a href="{{ route('appointments.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Book Appointment
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('appointments.index') }}" class="mb-4 flex items-center">
        @if(request()->has('upcoming'))
            <input type="hidden" name="upcoming" value="1">
        @endif

        <select name="status" class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2 w-1/4">
            <option value="">All Statuses</option>
            <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
            <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
        </select>
        
        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
            Filter
        </button>
        
        @if(request('status') || request('upcoming'))
            <a href="{{ route('appointments.index') }}" class="ml-3 text-gray-500 hover:text-gray-700 text-sm">Clear Filters</a>
        @endif
    </form>

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full leading-normal border-collapse">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 font-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                        <p class="text-gray-600">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 font-semibold">{{ $appointment->client?->first_name }} {{ $appointment->client?->last_name }}</p>
                        <p class="text-xs text-gray-500 italic">assigned to: {{ $appointment->staff?->name }}</p>
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900">{{ $appointment->service_type }}</p>
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <form action="{{ route('appointments.update-status', $appointment) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="text-xs border rounded py-1 px-1 text-gray-700 focus:outline-none w-full">
                                
                                {{-- Only show 'Scheduled' if the current status is Scheduled --}}
                                @if($appointment->status === 'Scheduled')
                                    <option value="Scheduled" selected>Scheduled</option>
                                @endif

                                {{-- Only show 'Confirmed' if the current status is Scheduled OR Confirmed --}}
                                @if(in_array($appointment->status, ['Scheduled', 'Confirmed']))
                                    <option value="Confirmed" {{ $appointment->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                @endif

                                {{-- These always show up as options --}}
                                <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="No Show">No Show</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </form>
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        @if($appointment->status === 'Completed' && auth()->user()->hasRole('Admin', 'Staff'))
                            <a href="{{ route('service-records.create', ['appointment_id' => $appointment->id]) }}" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1 px-3 rounded">
                                + Add Record
                            </a>
                        @else
                            <span class="text-gray-400 text-xs italic">In Progress</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($appointments->hasPages())
            <div class="mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6 rounded-b-lg shadow-sm">
            {{ $appointments->links() }}
            </div>
        @endif

</div>
@endsection