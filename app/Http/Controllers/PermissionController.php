<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Sample data - replace with actual database query
        $permissions = [
            ['id' => 1, 'name' => 'View Dashboard', 'slug' => 'dashboard.view', 'description' => 'Access to view dashboard', 'group' => 'Dashboard', 'roles_count' => 5, 'status' => 'active'],
            ['id' => 2, 'name' => 'View Stats', 'slug' => 'dashboard.stats', 'description' => 'Access to view statistics', 'group' => 'Dashboard', 'roles_count' => 3, 'status' => 'active'],
            ['id' => 3, 'name' => 'View Timesheet', 'slug' => 'timesheet.view', 'description' => 'Access to view timesheet entries', 'group' => 'Timesheet', 'roles_count' => 8, 'status' => 'active'],
            ['id' => 4, 'name' => 'Create Timesheet Entry', 'slug' => 'timesheet.create', 'description' => 'Create new timesheet entries', 'group' => 'Timesheet', 'roles_count' => 7, 'status' => 'active'],
            ['id' => 5, 'name' => 'Edit Timesheet Entry', 'slug' => 'timesheet.edit', 'description' => 'Edit existing timesheet entries', 'group' => 'Timesheet', 'roles_count' => 6, 'status' => 'active'],
            ['id' => 6, 'name' => 'Delete Timesheet Entry', 'slug' => 'timesheet.delete', 'description' => 'Delete timesheet entries', 'group' => 'Timesheet', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 7, 'name' => 'Approve Timesheet', 'slug' => 'timesheet.approve', 'description' => 'Approve timesheet submissions', 'group' => 'Timesheet', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 8, 'name' => 'View Leave', 'slug' => 'leave.view', 'description' => 'Access to view leave requests', 'group' => 'Leave', 'roles_count' => 8, 'status' => 'active'],
            ['id' => 9, 'name' => 'Request Leave', 'slug' => 'leave.request', 'description' => 'Submit leave requests', 'group' => 'Leave', 'roles_count' => 10, 'status' => 'active'],
            ['id' => 10, 'name' => 'Approve Leave', 'slug' => 'leave.approve', 'description' => 'Approve leave requests', 'group' => 'Leave', 'roles_count' => 3, 'status' => 'active'],
            ['id' => 11, 'name' => 'View Users', 'slug' => 'users.view', 'description' => 'Access to view user list', 'group' => 'Users', 'roles_count' => 6, 'status' => 'active'],
            ['id' => 12, 'name' => 'Create User', 'slug' => 'users.create', 'description' => 'Create new user accounts', 'group' => 'Users', 'roles_count' => 3, 'status' => 'active'],
            ['id' => 13, 'name' => 'Edit User', 'slug' => 'users.edit', 'description' => 'Edit user information', 'group' => 'Users', 'roles_count' => 4, 'status' => 'active'],
            ['id' => 14, 'name' => 'Delete User', 'slug' => 'users.delete', 'description' => 'Delete user accounts', 'group' => 'Users', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 15, 'name' => 'View Roles', 'slug' => 'roles.view', 'description' => 'Access to view roles', 'group' => 'Roles', 'roles_count' => 6, 'status' => 'active'],
            ['id' => 16, 'name' => 'Create Role', 'slug' => 'roles.create', 'description' => 'Create new roles', 'group' => 'Roles', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 17, 'name' => 'Edit Role', 'slug' => 'roles.edit', 'description' => 'Edit role details', 'group' => 'Roles', 'roles_count' => 3, 'status' => 'active'],
            ['id' => 18, 'name' => 'Delete Role', 'slug' => 'roles.delete', 'description' => 'Delete roles', 'group' => 'Roles', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 19, 'name' => 'View Permissions', 'slug' => 'permissions.view', 'description' => 'Access to view permissions', 'group' => 'Permissions', 'roles_count' => 5, 'status' => 'active'],
            ['id' => 20, 'name' => 'Create Permission', 'slug' => 'permissions.create', 'description' => 'Create new permissions', 'group' => 'Permissions', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 21, 'name' => 'Edit Permission', 'slug' => 'permissions.edit', 'description' => 'Edit permission details', 'group' => 'Permissions', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 22, 'name' => 'Delete Permission', 'slug' => 'permissions.delete', 'description' => 'Delete permissions', 'group' => 'Permissions', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 23, 'name' => 'Manage Payroll', 'slug' => 'payroll.manage', 'description' => 'Access to payroll management', 'group' => 'Payroll', 'roles_count' => 2, 'status' => 'active'],
            ['id' => 24, 'name' => 'View Reports', 'slug' => 'reports.view', 'description' => 'Access to view reports', 'group' => 'Reports', 'roles_count' => 5, 'status' => 'inactive'],
        ];

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $permissions = array_filter($permissions, function($permission) use ($search) {
                return str_contains(strtolower($permission['name']), $search) || 
                       str_contains(strtolower($permission['slug']), $search) ||
                       str_contains(strtolower($permission['description']), $search) ||
                       str_contains(strtolower($permission['group']), $search);
            });
            $permissions = array_values($permissions);
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $permissions = array_filter($permissions, function($permission) use ($request) {
                return $permission['status'] === $request->status;
            });
            $permissions = array_values($permissions);
        }

        // Group filter
        if ($request->has('group') && !empty($request->group)) {
            $permissions = array_filter($permissions, function($permission) use ($request) {
                return $permission['group'] === $request->group;
            });
            $permissions = array_values($permissions);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            usort($permissions, function($a, $b) use ($request) {
                switch ($request->sort) {
                    case 'name':
                        return strcmp($a['name'], $b['name']);
                    case 'group':
                        return strcmp($a['group'], $b['group']);
                    case 'roles':
                        return $b['roles_count'] - $a['roles_count']; // Descending
                    case 'date':
                        return $b['id'] - $a['id']; // Descending by ID as proxy for date
                    default:
                        return 0;
                }
            });
        }

        // Get unique groups for filter dropdown
        $groups = ['Dashboard', 'Timesheet', 'Leave', 'Users', 'Roles', 'Permissions', 'Payroll', 'Reports'];

        // Paginate
        $perPage = 5;
        $page = $request->get('page', 1);
        $total = count($permissions);
        $permissions = array_slice($permissions, ($page - 1) * $perPage, $perPage);

        // Create a paginator-like object
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $permissions,
            $total,
            $perPage,
            $page,
            ['path' => route('permissions.index', [], false)]
        );

        return view('permissions.index', compact('paginator', 'permissions', 'groups'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        $groups = ['Dashboard', 'Timesheet', 'Leave', 'Users', 'Roles', 'Permissions', 'Payroll', 'Reports'];
        return view('permissions.create', compact('groups'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug',
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // TODO: Save to database
        // Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing a permission.
     */
    public function edit($id)
    {
        $groups = ['Dashboard', 'Timesheet', 'Leave', 'Users', 'Roles', 'Permissions', 'Payroll', 'Reports'];
        return view('permissions.edit', ['id' => $id, 'groups' => $groups]);
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $id,
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // TODO: Update in database
        // Permission::where('id', $id)->update($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Show the form for changing permission status.
     */
    public function showStatus($id)
    {
        return view('permissions.status', ['id' => $id]);
    }

    /**
     * Update the status of the specified permission.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:500',
        ]);

        // TODO: Update status in database
        // Permission::where('id', $id)->update(['status' => $validated['status']]);

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';
        
        return redirect()->route('permissions.index')->with('success', "Permission {$statusMessage} successfully.");
    }

    /**
     * Remove the specified permission.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // Permission::where('id', $id)->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}

