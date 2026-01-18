<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Sample data - replace with actual database query
        $departments = [
            ['id' => 1, 'name' => 'Information Technology', 'code' => 'IT', 'description' => 'Handles all IT operations and development', 'status' => 'active', 'created_at' => '2023-01-15'],
            ['id' => 2, 'name' => 'Human Resources', 'code' => 'HR', 'description' => 'Manages employee relations and policies', 'status' => 'active', 'created_at' => '2023-01-20'],
            ['id' => 3, 'name' => 'Finance', 'code' => 'FIN', 'description' => 'Manages financial operations and accounting', 'status' => 'active', 'created_at' => '2023-02-01'],
            ['id' => 4, 'name' => 'Marketing', 'code' => 'MKT', 'description' => 'Handles marketing and brand management', 'status' => 'active', 'created_at' => '2023-02-15'],
            ['id' => 5, 'name' => 'Operations', 'code' => 'OPS', 'description' => 'Oversees daily operations and logistics', 'status' => 'inactive', 'created_at' => '2023-03-01'],
        ];

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $departments = array_filter($departments, function($department) use ($search) {
                return str_contains(strtolower($department['name']), $search) ||
                       str_contains(strtolower($department['code']), $search) ||
                       str_contains(strtolower($department['description']), $search);
            });
            $departments = array_values($departments);
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $departments = array_filter($departments, function($department) use ($request) {
                return $department['status'] === $request->status;
            });
            $departments = array_values($departments);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'name':
                    usort($departments, fn($a, $b) => strcmp($a['name'], $b['name']));
                    break;
                case 'code':
                    usort($departments, fn($a, $b) => strcmp($a['code'], $b['code']));
                    break;
                case 'date':
                    usort($departments, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
                    break;
                default:
                    break;
            }
        }

        // Paginate
        $perPage = 5;
        $page = $request->get('page', 1);
        $total = count($departments);
        $departments = array_slice($departments, ($page - 1) * $perPage, $perPage);

        // Create a paginator-like object
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $departments,
            $total,
            $perPage,
            $page,
            ['path' => route('departments.index', [], false)]
        );

        return view('departments.index', compact('paginator', 'departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code',
        ]);

        // TODO: Save to database
        // Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing a department.
     */
    public function edit($id)
    {
        // Sample department data - replace with actual database query
        $department = [
            'id' => $id,
            'name' => 'Information Technology',
            'code' => 'IT',
            'description' => 'Handles all IT operations and development',
            'status' => 'active'
        ];

        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
        ]);

        // TODO: Update in database
        // Department::where('id', $id)->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Show the form for changing department status.
     */
    public function showStatus($id)
    {
        return view('departments.status', ['id' => $id]);
    }

    /**
     * Update the status of the specified department.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:500',
        ]);

        // TODO: Update status in database
        // Department::where('id', $id)->update(['status' => $validated['status']]);

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

        return redirect()->route('departments.index')->with('success', "Department {$statusMessage} successfully.");
    }

    /**
     * Remove the specified department.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // Department::where('id', $id)->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}