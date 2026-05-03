<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            // Wrap in a closure so the "OR" doesn't break other potential filters
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(10);
        $clients->appends($request->all());
        
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        Client::create($request->validated());

        return redirect()->route('clients.index')
                         ->with('success', 'Client created successfully.');
    }

    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('clients.index')->with('error', 'Cannot delete this client because they have existing history.');
        }
    }


    public function show(Client $client)
    {
        /** * IMPORTANT: We use withTrashed() here so the client's history 
         * includes appointments that were cancelled or moved to Service Records.
         */
        $appointments = $client->appointments()
            ->withTrashed()
            ->with('staff')
            ->latest('appointment_date')
            ->get();
        
        return view('clients.show', compact('client', 'appointments'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

   public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'nullable|email|max:255|unique:clients,email,' . $client->id,
            'phone'      => 'required|numeric', 
            'address'    => 'nullable|string|max:500',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully!');
    }
}
