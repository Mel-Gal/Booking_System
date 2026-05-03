@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Overview</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('clients.index') }}" class="block transform hover:scale-105 transition-all duration-300">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500 hover:bg-blue-50">
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Total Clients</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalClients }}</p>
        </div>
    </a>

    <a href="{{ route('appointments.index') }}" class="block transform hover:scale-105 transition-all duration-300">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500 hover:bg-green-50">
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Appointments Today</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $appointmentsToday }}</p>
        </div>
    </a>

    <a href="{{ route('service-records.index') }}" class="block transform hover:scale-105 transition-all duration-300">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500 hover:bg-indigo-50">
            <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Total Completed</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $completedAppointments }}</p>
        </div>
    </a>
</div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Upcoming Appointments</h3>
                <a href="{{ route('appointments.index') }}" class="text-blue-500 hover:text-blue-700 text-sm font-bold">View All</a>
            </div>
            
            @if($upcomingAppointments->isEmpty())
                <p class="text-gray-600 text-sm">No upcoming appointments scheduled.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($upcomingAppointments as $appointment)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $appointment->client?->first_name ?? 'Unknown' }} {{ $appointment->client?->last_name ?? 'Client' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $appointment->service_type }} with {{ $appointment->staff?->name ?? 'Unknown Staff' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h3>
            
            @if($recentActivity->isEmpty())
                <p class="text-gray-600 text-sm">No recent activity found.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($recentActivity as $activity)
                        <li class="py-3">
                            <p class="text-sm text-gray-800">
                                <span class="font-bold">{{ $activity->client?->first_name ?? 'Unknown Client' }}'s</span> appointment was marked as 
                                <span class="font-semibold 
                                    {{ $activity->status === 'Completed' ? 'text-green-600' : ($activity->status === 'Cancelled' ? 'text-red-600' : 'text-blue-600') }}">
                                    {{ $activity->status }}
                                </span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Updated {{ $activity->updated_at->diffForHumans() }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
</div>
@endsection