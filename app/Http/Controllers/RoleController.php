<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of roles with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Sample data - replace with actual database query
        $roles = [
            ['id' => 1, 'name' => 'Administrator', 'slug' => 'admin', 'description' => 'Full system access with all permissions', 'users' => 5, 'permissions' => 15, 'status' => 'active'],
            ['id' => 2, 'name' => 'Manager', 'slug' => 'manager', 'description' => 'Team management and reporting access', 'users' => 12, 'permissions' => 8, 'status' => 'active'],
            ['id' => 3, 'name' => 'Developer', 'slug' => 'developer', 'description' => 'Code development and bug fixing access', 'users' => 25, 'permissions' => 5, 'status' => 'active'],
            ['id' => 4, 'name' => 'HR Manager', 'slug' => 'hr_manager', 'description' => 'Human resources and employee management', 'users' => 3, 'permissions' => 6, 'status' => 'active'],
            ['id' => 5, 'name' => 'Viewer', 'slug' => 'viewer', 'description' => 'Read-only access to public data', 'users' => 50, 'permissions' => 3, 'status' => 'inactive'],
            ['id' => 6, 'name' => 'Editor', 'slug' => 'editor', 'description' => 'Content creation and editing access', 'users' => 8, 'permissions' => 7, 'status' => 'active'],
            ['id' => 7, 'name' => 'Support', 'slug' => 'support', 'description' => 'Customer support and ticket management', 'users' => 15, 'permissions' => 4, 'status' => 'active'],
            ['id' => 8, 'name' => 'Analyst', 'slug' => 'analyst', 'description' => 'Data analysis and reporting access', 'users' => 6, 'permissions' => 9, 'status' => 'active'],
            ['id' => 9, 'name' => 'Accountant', 'slug' => 'accountant', 'description' => 'Financial and accounting permissions', 'users' => 4, 'permissions' => 11, 'status' => 'active'],
            ['id' => 10, 'name' => 'Auditor', 'slug' => 'auditor', 'description' => 'System audit and compliance access', 'users' => 2, 'permissions' => 6, 'status' => 'active'],
            ['id' => 11, 'name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Marketing campaign management', 'users' => 7, 'permissions' => 5, 'status' => 'active'],
            ['id' => 12, 'name' => 'Intern', 'slug' => 'intern', 'description' => 'Limited access for interns', 'users' => 10, 'permissions' => 2, 'status' => 'inactive'],
        ];

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $roles = array_filter($roles, function($role) use ($search) {
                return str_contains(strtolower($role['name']), $search) || 
                       str_contains(strtolower($role['slug']), $search) ||
                       str_contains(strtolower($role['description']), $search);
            });
            $roles = array_values($roles);
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $roles = array_filter($roles, function($role) use ($request) {
                return $role['status'] === $request->status;
            });
            $roles = array_values($roles);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            usort($roles, function($a, $b) use ($request) {
                switch ($request->sort) {
                    case 'name':
                        return strcmp($a['name'], $b['name']);
                    case 'users':
                        return $b['users'] - $a['users']; // Descending
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
        $total = count($roles);
        $roles = array_slice($roles, ($page - 1) * $perPage, $perPage);

        // Create a paginator-like object
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $roles,
            $total,
            $perPage,
            $page,
            ['path' => route('roles.index', [], false)]
        );

        return view('roles.index', compact('paginator', 'roles'));
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
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
        ]);

        // TODO: Save to database
        // Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing a role.
     */
    public function edit($id)
    {
        return view('roles.edit', ['id' => $id]);
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
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
        ]);

        // TODO: Update in database
        // Role::where('id', $id)->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Show the form for changing role status.
     */
    public function showStatus($id)
    {
        return view('roles.status', ['id' => $id]);
    }

    /**
     * Update the status of the specified role.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:500',
        ]);

        // TODO: Update status in database
        // Role::where('id', $id)->update(['status' => $validated['status']]);

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';
        
        return redirect()->route('roles.index')->with('success', "Role {$statusMessage} successfully.");
    }

    /**
     * Remove the specified role.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // Role::where('id', $id)->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
