<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\Crypt;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = Department::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if ($sort == 'date') {
            $sort = 'created_at';
        }

        $query->orderBy($sort, $direction);

        // Paginate
        $departments = $query->paginate(10);

        return view('departments.index', compact('departments'));
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
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'required|string|max:10|unique:departments,code',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['status'] = 'active';

        Department::create($validated);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing a department.
     */
    public function edit($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'code' => 'required|string|max:10|unique:departments,code,' . $id,
            'description' => 'nullable|string|max:500',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Show the form for changing department status.
     */
    public function showStatus($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $department = Department::findOrFail($id);
        return view('departments.status', compact('department'));
    }

    /**
     * Update the status of the specified department.
     */
    public function updateStatus(Request $request, $encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'reason' => 'nullable|string|max:500',
        ]);

        $department = Department::findOrFail($id);
        $department->update(['status' => $validated['status']]);

        $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Department {$statusMessage} successfully.",
                'status' => $validated['status']
            ]);
        }

        return redirect()->route('admin.departments.index')->with('success', "Department {$statusMessage} successfully.");
    }

    /**
     * Remove the specified department.
     */
    public function destroy($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }
}