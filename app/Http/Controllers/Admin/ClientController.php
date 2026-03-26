<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ContactPerson;

class ClientController extends Controller
{
    /**
     * Display a listing of clients with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Query clients from database
        $query = Client::orderBy('created_at', 'desc');

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'date':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        // Paginate
        $perPage = $request->get('per_page', 10);
        $clients = $query->paginate($perPage);

        return view('Admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('Admin.clients.create');
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'contact_persons' => 'nullable|array',
            'contact_persons.*.name' => 'required|string|max:255',
            'contact_persons.*.designation' => 'nullable|string|max:255',
            'contact_persons.*.email' => 'required|email',
            'contact_persons.*.phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        // Save client data without contact_persons
        $clientData = $validated;
        unset($clientData['contact_persons']);
        
        $client = Client::create($clientData);

        // Save contact persons
        if (!empty($validated['contact_persons'])) {
            foreach ($validated['contact_persons'] as $contact) {
                ContactPerson::create([
                    'client_id' => $client->id,
                    'name' => $contact['name'],
                    'designation' => $contact['designation'] ?? null,
                    'email' => $contact['email'],
                    'phone' => $contact['phone'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Show the form for editing a client.
     */
    public function edit($id)
    {
        // Get client from database with contact persons
        $client = Client::with('contactPersons')->findOrFail($id);

        return view('Admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'contact_persons' => 'nullable|array',
            'contact_persons.*.name' => 'required|string|max:255',
            'contact_persons.*.designation' => 'nullable|string|max:255',
            'contact_persons.*.email' => 'required|email',
            'contact_persons.*.phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        // Get client
        $client = Client::findOrFail($id);

        // Update client data without contact_persons
        $clientData = $validated;
        unset($clientData['contact_persons']);
        
        $client->update($clientData);

        // Update contact persons - delete old and create new
        ContactPerson::where('client_id', $client->id)->delete();
        if (!empty($validated['contact_persons'])) {
            foreach ($validated['contact_persons'] as $contact) {
                ContactPerson::create([
                    'client_id' => $client->id,
                    'name' => $contact['name'],
                    'designation' => $contact['designation'] ?? null,
                    'email' => $contact['email'],
                    'phone' => $contact['phone'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Show the form for changing client status.
     */
    public function showStatus($id)
    {
        $client = Client::findOrFail($id);
        return view('Admin.clients.status', compact('client'));
    }

    /**
     * Update the status of the specified client.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:500',
        ]);

        // Update status in database
        Client::where('id', $id)->update(['status' => $validated['status']]);

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

        return redirect()->route('admin.clients.index')->with('success', "Client {$statusMessage} successfully.");
    }

    /**
     * Remove the specified client.
     */
    public function destroy($id)
    {
        // Delete contact persons first
        ContactPerson::where('client_id', $id)->delete();
        
        // Delete from database
        Client::where('id', $id)->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }

    /**
     * Search clients for dropdown (AJAX)
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        $clients = Client::where('status', 'active')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email']);

        return response()->json($clients);
    }
}