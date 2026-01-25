<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of roles with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = Role::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', (bool) $request->status);
        }


        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'name':
                    $query->orderBy('name');
                    break;
                case 'date':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $roles = $query->paginate(5);

        return view('roles.index', compact('roles'));
    }
    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing a role.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:500',
        ]);

        $role = Role::findOrFail($id);
        $role->update($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Show the form for changing role status.
     */
    public function showStatus($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.status', compact('role'));
    }

    /**
     * Update the status of the specified role.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'boolean',
            'reason' => 'nullable|string|max:500',
        ]);

        $role = Role::findOrFail($id);
        $role->update(['status' => $validated['status']]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

        return redirect()->route('admin.roles.index')->with('success', "Role {$statusMessage} successfully.");
    }

    /**
     * Remove the specified role.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
