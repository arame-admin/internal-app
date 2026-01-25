<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('group', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Group filter
        if ($request->has('group') && !empty($request->group)) {
            $query->where('group', $request->group);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'name':
                    $query->orderBy('name');
                    break;
                case 'group':
                    $query->orderBy('group');
                    break;
                case 'roles':
                    $query->withCount('roles')->orderBy('roles_count', 'desc');
                    break;
                case 'date':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get unique groups for filter dropdown
        $groups = Permission::distinct()->pluck('group')->sort();

        // Paginate
        $permissions = $query->paginate(10);

        return view('permissions.index', compact('permissions', 'groups'));
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

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing a permission.
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $groups = Permission::distinct()->pluck('group')->sort();
        return view('permissions.edit', compact('permission', 'groups'));
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

        $permission = Permission::findOrFail($id);
        $permission->update($validated);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Show the form for changing permission status.
     */
    public function showStatus($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.status', compact('permission'));
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

        $permission = Permission::findOrFail($id);
        $permission->update(['status' => $validated['status']]);

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

        return redirect()->route('admin.permissions.index')->with('success', "Permission {$statusMessage} successfully.");
    }

    /**
     * Remove the specified permission.
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}

