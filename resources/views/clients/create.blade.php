@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h2 class="text-2xl font-semibold mb-6">Add New Client</h2>

    <form action="{{ route('clients.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf

        <div class="mb-4 flex space-x-4">
            <div class="w-1/2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">First Name <span class="text-red-500">*</span></label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('first_name') border-red-500 @enderror">
                @error('first_name') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="w-1/2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">Last Name <span class="text-red-500">*</span></label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('last_name') border-red-500 @enderror">
                @error('last_name') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4 flex space-x-4">
            <div class="w-1/2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="phone" 
                    id="phone" 
                    value="{{ old('phone') }}" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    placeholder="Numbers only"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror"
                >
                @error('phone') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="w-1/2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="address">Address</label>
            <input type="text" name="address" id="address" value="{{ old('address') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">Internal Notes</label>
            <textarea name="notes" id="notes" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Save Client
            </button>
            <a href="{{ route('clients.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection