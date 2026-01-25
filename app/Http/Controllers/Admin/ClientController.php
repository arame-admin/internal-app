<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    /**
     * Display a listing of clients with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Sample data - replace with actual database query
        $clients = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '+1-555-0123', 'address' => '123 Main St, NY', 'contact_persons' => [['name' => 'John Contact', 'designation' => 'Manager', 'email' => 'john.contact@example.com', 'phone' => '+1-555-0123']], 'projects' => 5, 'status' => 'active'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '+1-555-0124', 'address' => '456 Oak Ave, CA', 'contact_persons' => [['name' => 'Jane Contact', 'designation' => 'Director', 'email' => 'jane.contact@example.com', 'phone' => '+1-555-0124']], 'projects' => 3, 'status' => 'active'],
            ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'phone' => '+1-555-0125', 'address' => '789 Pine Rd, TX', 'contact_persons' => [['name' => 'Bob Contact', 'designation' => 'CEO', 'email' => 'bob.contact@example.com', 'phone' => '+1-555-0125']], 'projects' => 2, 'status' => 'inactive'],
            ['id' => 4, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'phone' => '+1-555-0126', 'address' => '321 Elm St, FL', 'contact_persons' => [['name' => 'Alice Contact', 'designation' => 'CTO', 'email' => 'alice.contact@example.com', 'phone' => '+1-555-0126']], 'projects' => 7, 'status' => 'active'],
            ['id' => 5, 'name' => 'Charlie Wilson', 'email' => 'charlie@example.com', 'phone' => '+1-555-0127', 'address' => '654 Maple Dr, WA', 'contact_persons' => [['name' => 'Charlie Contact', 'designation' => 'VP', 'email' => 'charlie.contact@example.com', 'phone' => '+1-555-0127']], 'projects' => 1, 'status' => 'active'],
            ['id' => 6, 'name' => 'Diana Davis', 'email' => 'diana@example.com', 'phone' => '+1-555-0128', 'address' => '987 Cedar Ln, IL', 'contact_persons' => [['name' => 'Diana Contact', 'designation' => 'Manager', 'email' => 'diana.contact@example.com', 'phone' => '+1-555-0128']], 'projects' => 4, 'status' => 'active'],
            ['id' => 7, 'name' => 'Edward Miller', 'email' => 'edward@example.com', 'phone' => '+1-555-0129', 'address' => '147 Birch St, GA', 'contact_persons' => [['name' => 'Edward Contact', 'designation' => 'Director', 'email' => 'edward.contact@example.com', 'phone' => '+1-555-0129']], 'projects' => 6, 'status' => 'inactive'],
            ['id' => 8, 'name' => 'Fiona Garcia', 'email' => 'fiona@example.com', 'phone' => '+1-555-0130', 'address' => '258 Spruce Ave, CO', 'contact_persons' => [['name' => 'Fiona Contact', 'designation' => 'CEO', 'email' => 'fiona.contact@example.com', 'phone' => '+1-555-0130']], 'projects' => 3, 'status' => 'active'],
        ];

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $clients = array_filter($clients, function($client) use ($search) {
                return str_contains(strtolower($client['name']), $search) ||
                       str_contains(strtolower($client['email']), $search) ||
                       str_contains(strtolower($client['company']), $search);
            });
            $clients = array_values($clients);
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $clients = array_filter($clients, function($client) use ($request) {
                return $client['status'] === $request->status;
            });
            $clients = array_values($clients);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            usort($clients, function($a, $b) use ($request) {
                switch ($request->sort) {
                    case 'name':
                        return strcmp($a['name'], $b['name']);
                    case 'projects':
                        return $b['projects'] - $a['projects']; // Descending
                    case 'date':
                        return $b['id'] - $a['id']; // Descending by ID as proxy for date
                    default:
                        return 0;
                }
            });
        }

        // Paginate
        $perPage = 5;
        $page = $request->get('page', 1);
        $total = count($clients);
        $clients = array_slice($clients, ($page - 1) * $perPage, $perPage);

        // Create a paginator-like object
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $clients,
            $total,
            $perPage,
            $page,
            ['path' => route('admin.clients.index', [], false)]
        );

        return view('clients.index', compact('paginator', 'clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clients.create');
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

        // TODO: Save to database
        // Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Show the form for editing a client.
     */
    public function edit($id)
    {
        // Sample data - replace with actual database query
        $client = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1-555-0123',
            'address' => '123 Main St, NY',
            'contact_persons' => [
                ['name' => 'John Contact', 'designation' => 'Manager', 'email' => 'john.contact@example.com', 'phone' => '+1-555-0123']
            ],
            'status' => 'active'
        ];

        return view('clients.edit', compact('client', 'id'));
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

        // TODO: Update in database
        // Client::where('id', $id)->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Show the form for changing client status.
     */
    public function showStatus($id)
    {
        return view('clients.status', ['id' => $id]);
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

        // TODO: Update status in database
        // Client::where('id', $id)->update(['status' => $validated['status']]);

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

        return redirect()->route('clients.index')->with('success', "Client {$statusMessage} successfully.");
    }

    /**
     * Remove the specified client.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // Client::where('id', $id)->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}