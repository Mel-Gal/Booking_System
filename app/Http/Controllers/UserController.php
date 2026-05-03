<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show the list of users
    public function index()
{
    // Added pagination (10 per page)
    $users = User::orderBy('name')->paginate(10);
    return view('users.index', compact('users'));
}

    // Show the form to create a new user
    public function create()
    {
        return view('users.create');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'role' => 'required|in:Admin,Medical Staff,Receptionist', // Matches your CAMS roles
    ]);

    $user->update($validated);

    return redirect()->route('users.index')->with('success', 'User updated successfully!');
}
    // Save the new user to the database
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'role' => 'required|in:Admin,Staff,Receptionist',
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'role' => $validated['role'],
        // Everyone starts with 'password123'
        'password' => Hash::make('password123'), 
        // We keep this true so the system can prompt them to change it later
        'must_change_password' => true, 
    ]);

    return redirect()->route('users.index')
        ->with('success', 'User created! Default password is: password123');
}

    public function destroy(User $user)
{
    // Optional: Prevent the Admin from deleting themselves
    if (auth()->id() === $user->id) {
        return redirect()->route('users.index')->with('error', 'You cannot delete your own account!');
    }

    $user->delete();

    return redirect()->route('users.index')->with('success', 'User deleted successfully.');
}
}