@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">User Management</h2>
        <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Add New User
        </a>
    </div>

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-max w-full table-auto">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-center">System Role</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left font-medium">{{ $user->name }}</td>
                        <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                        <td class="py-3 px-6 text-center">
                            @php $checkRole = strtolower(trim($user->role)); @endphp
                            @if($checkRole === 'admin')
                                <span class="bg-purple-100 text-purple-700 py-1 px-3 rounded-full text-xs font-bold uppercase">Admin</span>
                            @elseif($checkRole === 'staff')
                                <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-bold uppercase">Medical Staff</span>
                            @else
                                <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-bold uppercase">Receptionist</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-4">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700 font-medium">
                                    Edit
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())
            <div class="mt-4 px-4 py-3 bg-white border-t border-gray-200 sm:px-6 rounded-b-lg shadow-sm">
            {{ $users->links() }}
            </div>
        @endif
</div>
@endsection