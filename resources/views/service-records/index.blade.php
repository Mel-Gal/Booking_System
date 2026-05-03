@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold leading-tight text-gray-800">Service Records</h2>
        <a href="{{ route('service-records.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Record
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

            <form action="{{ route('service-records.index') }}" method="GET" class="mb-4 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Client or Staff..." class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-1/3">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('service-records.index') }}" class="mt-2 text-sm text-gray-600 underline">Clear Filters</a>
            @endif
        </form>
    <div class="bg-white shadow-md rounded my-6 overflow-hidden">
        <table class="min-w-full leading-normal border-collapse">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Staff / Service</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
    @forelse($serviceRecords as $record)
    <tr>
        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <p class="text-gray-900 whitespace-no-wrap font-bold">{{ \Carbon\Carbon::parse($record->service_date)->format('M d, Y') }}</p>
        </td>
        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <p class="text-gray-900 whitespace-no-wrap">{{ $record->client?->first_name ?? 'Unknown' }} {{ $record->client?->last_name ?? 'Client' }}</p>
        </td>
        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <p class="text-gray-900 whitespace-no-wrap">{{ $record->staff?->name ?? 'Unknown Staff' }}</p>
            <p class="text-gray-500 text-xs">{{ $record->appointment?->service_type ?? 'Unknown Service' }}</p>
        </td>
        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <div class="flex items-center space-x-3">
                @if(auth()->user()->hasRole('Admin', 'Staff'))
                    <a href="{{ route('service-records.edit', $record) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs">Edit</a>
                    <a href="{{ route('service-records.show', $record) }}" class="text-blue-600 hover:text-blue-900 font-bold text-xs mr-2">
                        View
                    </a>
                @endif

                @if(auth()->user()->hasRole('Admin'))
                    <form action="{{ route('service-records.destroy', $record) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
            No service records found.
        </td>
    </tr>
    @endforelse
</tbody>
        </table>
    </div>
    @if ($serviceRecords->hasPages())
            <div class="mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6 rounded-b-lg shadow-sm">
            {{ $serviceRecords->links() }}
            </div>
        @endif

</div>
@endsection