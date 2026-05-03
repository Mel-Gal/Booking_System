@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Service Record Details</h2>
            <a href="{{ route('service-records.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                &larr; Back to Records
            </a>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase">Date</p>
                    <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($serviceRecord->service_date)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase">Client</p>
                    <p class="text-gray-900 font-medium">{{ $serviceRecord->client?->first_name ?? 'Unknown' }} {{ $serviceRecord->client?->last_name ?? 'Client' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase">Attending Staff</p>
                    <p class="text-gray-900 font-medium">{{ $serviceRecord->staff?->name ?? 'Unknown Staff' }}</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Service Description</p>
                <div class="bg-gray-50 p-4 rounded break-words whitespace-pre-wrap text-gray-700">{{ $serviceRecord->description }}</div>
            </div>

            @if($serviceRecord->remarks)
            <div class="mb-6">
                <p class="text-sm text-gray-500 font-semibold uppercase mb-2">Remarks</p>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded text-gray-800 whitespace-pre-wrap">{{ $serviceRecord->remarks }}</div>
            </div>
            @endif

            <div class="border-t pt-6 mt-6">
                <p class="text-sm text-gray-500 font-semibold uppercase mb-4">Appointment Status History</p>
                <div class="space-y-4">
                    @if($serviceRecord->appointment?->scheduled_at)
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">📅</div>
                        <div>
                            <p class="text-gray-900 font-bold">Scheduled</p>
                            <p class="text-gray-500">{{ $serviceRecord->appointment->scheduled_at->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($serviceRecord->appointment?->confirmed_at)
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mr-3">📞</div>
                        <div>
                            <p class="text-gray-900 font-bold">Confirmed</p>
                            <p class="text-gray-500">{{ $serviceRecord->appointment->confirmed_at->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($serviceRecord->appointment?->completed_at)
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-3">✅</div>
                        <div>
                            <p class="text-gray-900 font-bold">Completed</p>
                            <p class="text-gray-500">{{ $serviceRecord->appointment->completed_at->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection