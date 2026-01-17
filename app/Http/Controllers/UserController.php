<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Sample data - replace with actual database query
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Administrator', 'department' => 'IT', 'phone' => '+1 234-567-8901', 'status' => 'active', 'joined_date' => '2023-01-15'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'Manager', 'department' => 'HR', 'phone' => '+1 234-567-8902', 'status' => 'active', 'joined_date' => '2023-02-20'],
            ['id' => 3, 'name' => 'Michael Johnson', 'email' => 'michael@example.com', 'role' => 'Developer', 'department' => 'IT', 'phone' => '+1 234-567-8903', 'status' => 'active', 'joined_date' => '2023-03-10'],
            ['id' => 4, 'name' => 'Emily Davis', 'email' => 'emily@example.com', 'role' => 'HR Manager', 'department' => 'HR', 'phone' => '+1 234-567-8904', 'status' => 'active', 'joined_date' => '2023-04-05'],
            ['id' => 5, 'name' => 'Robert Wilson', 'email' => 'robert@example.com', 'role' => 'Developer', 'department' => 'IT', 'phone' => '+1 234-567-8905', 'status' => 'inactive', 'joined_date' => '2023-05-12'],
            ['id' => 6, 'name' => 'Sarah Brown', 'email' => 'sarah@example.com', 'role' => 'Editor', 'department' => 'Content', 'phone' => '+1 234-567-8906', 'status' => 'active', 'joined_date' => '2023-06-18'],
            ['id' => 7, 'name' => 'David Lee', 'email' => 'david@example.com', 'role' => 'Viewer', 'department' => 'Finance', 'phone' => '+1 234-567-8907', 'status' => 'active', 'joined_date' => '2023-07-22'],
            ['id' => 8, 'name' => 'Lisa Anderson', 'email' => 'lisa@example.com', 'role' => 'Support', 'department' => 'Support', 'phone' => '+1 234-567-8908', 'status' => 'active', 'joined_date' => '2023-08-30'],
            ['id' => 9, 'name' => 'James Taylor', 'email' => 'james@example.com', 'role' => 'Analyst', 'department' => 'Analytics', 'phone' => '+1 234-567-8909', 'status' => 'inactive', 'joined_date' => '2023-09-14'],
            ['id' => 10, 'name' => 'Jennifer Martinez', 'email' => 'jennifer@example.com', 'role' => 'Marketing', 'department' => 'Marketing', 'phone' => '+1 234-567-8910', 'status' => 'active', 'joined_date' => '2023-10-25'],
            ['id' => 11, 'name' => 'Christopher Garcia', 'email' => 'chris@example.com', 'role' => 'Accountant', 'department' => 'Finance', 'phone' => '+1 234-567-8911', 'status' => 'active', 'joined_date' => '2023-11-08'],
            ['id' => 12, 'name' => 'Amanda Rodriguez', 'email' => 'amanda@example.com', 'role' => 'Intern', 'department' => 'IT', 'phone' => '+1 234-567-8912', 'status' => 'active', 'joined_date' => '2024-01-05'],
        ];

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $users = array_filter($users, function($user) use ($search) {
                return str_contains(strtolower($user['name']), $search) || 
                       str_contains(strtolower($user['email']), $search) ||
                       str_contains(strtolower($user['department']), $search) ||
                       str_contains(strtolower($user['role']), $search);
            });
            $users = array_values($users);
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $users = array_filter($users, function($user) use ($request) {
                return $user['status'] === $request->status;
            });
            $users = array_values($users);
        }

        // Department filter
        if ($request->has('department') && !empty($request->department)) {
            $users = array_filter($users, function($user) use ($request) {
                return $user['department'] === $request->department;
            });
            $users = array_values($users);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            usort($users, function($a, $b) use ($request) {
                switch ($request->sort) {
                    case 'name':
                        return strcmp($a['name'], $b['name']);
                    case 'email':
                        return strcmp($a['email'], $b['email']);
                    case 'department':
                        return strcmp($a['department'], $b['department']);
                    case 'date':
                        return strcmp($b['joined_date'], $a['joined_date']);
                    default:
                        return 0;
                }
            });
        }

        // Get unique departments for filter dropdown
        $departments = ['IT', 'HR', 'Finance', 'Content', 'Support', 'Analytics', 'Marketing'];

        // Paginate
        $perPage = 5;
        $page = $request->get('page', 1);
        $total = count($users);
        $users = array_slice($users, ($page - 1) * $perPage, $perPage);

        // Create a paginator-like object
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $users,
            $total,
            $perPage,
            $page,
            ['path' => route('users.index', [], false)]
        );

        return view('users.index', compact('paginator', 'users', 'departments'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = ['Administrator', 'Manager', 'Developer', 'HR Manager', 'Viewer', 'Editor', 'Support', 'Analyst', 'Accountant', 'Auditor', 'Marketing', 'Intern'];
        $departments = ['IT', 'HR', 'Finance', 'Content', 'Support', 'Analytics', 'Marketing'];
        return view('users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        // TODO: Save to database
        // User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing a user.
     */
    public function edit($id)
    {
        $roles = ['Administrator', 'Manager', 'Developer', 'HR Manager', 'Viewer', 'Editor', 'Support', 'Analyst', 'Accountant', 'Auditor', 'Marketing', 'Intern'];
        $departments = ['IT', 'HR', 'Finance', 'Content', 'Support', 'Analytics', 'Marketing'];
        return view('users.edit', ['id' => $id, 'roles' => $roles, 'departments' => $departments]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        // TODO: Update in database
        // User::where('id', $id)->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Show the form for changing user status.
     */
    public function showStatus($id)
    {
        return view('users.status', ['id' => $id]);
    }

    /**
     * Update the status of the specified user.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:500',
        ]);

        // TODO: Update status in database
        // User::where('id', $id)->update(['status' => $validated['status']]);

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';
        
        return redirect()->route('users.index')->with('success', "User {$statusMessage} successfully.");
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // User::where('id', $id)->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

