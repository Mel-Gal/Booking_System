@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold leading-tight text-gray-800">Edit User: {{ $user->name }}</h2>
        <a href="{{ route('users.index') }}" class="text-blue-500 hover:text-blue-700 font-medium">
            &larr; Back to Users
        </a>
    </div>

    <div class="bg-white shadow-md rounded p-6">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Full Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="role">System Role *</label>
                <select name="role" id="role" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                    <option value="Staff" {{ $user->role == 'Staff' ? 'selected' : '' }}>Staff (Manage Appointments & Records)</option>
                    <option value="Receptionist" {{ $user->role == 'Receptionist' ? 'selected' : '' }}>Receptionist (Manage Appointments Only)</option>
                </select>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection