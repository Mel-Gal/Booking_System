@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Add New User</h2>

    <div class="bg-white shadow-md rounded p-6">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                <input type="text" name="name" required class="shadow border rounded w-full py-2 px-3">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                <input type="email" name="email" required class="shadow border rounded w-full py-2 px-3">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">System Role</label>
                <select name="role" required class="shadow border rounded w-full py-2 px-3">
                    <option value="Admin">Admin</option>
                    <option value="Staff">Medical Staff</option>
                    <option value="Receptionist">Receptionist</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection