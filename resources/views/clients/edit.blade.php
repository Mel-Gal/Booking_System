@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Edit Client: {{ $client->first_name }} {{ $client->last_name }}</h2>

    <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $client->first_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $client->last_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $client->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Phone Number (Numbers Only)</label>
            <input type="text" 
                   name="phone" 
                   value="{{ old('phone', $client->phone) }}" 
                   oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                   required>
            <p class="text-xs text-gray-500 mt-1">Letters and symbols will be automatically removed.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <textarea name="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address', $client->address) }}</textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Client</button>
        </div>
    </form>
</div>
@endsection