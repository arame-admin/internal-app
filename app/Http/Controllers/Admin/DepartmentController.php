<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * DepartmentController handles CRUD operations for departments.
 *
 * This controller provides methods to manage departments including listing,
 * creating, updating, deleting, and toggling status. It includes validation,
 * logging, and proper error handling.
 *
 * @package App\Http\Controllers\Admin
 */
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
     *
     * Validates the input data, creates a new department with active status,
     * logs the operation, and redirects to the index page.
     *
     * @param Request $request The HTTP request containing department data
     * @return \Illuminate\Http\RedirectResponse Redirect to departments index
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:departments,name',
                'code' => 'required|string|max:10|unique:departments,code',
                'description' => 'nullable|string|max:500',
            ]);

            $validated['status'] = 'active';

            Department::create($validated);

            Log::channel('custom')->info('Department created successfully: ' . $validated['name']);

            return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error creating department: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create department.'])->withInput();
        } finally {
            Log::channel('custom')->info('Store method executed for department');
        }
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
        try {
            $id = Crypt::decrypt($encryptedId);
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:departments,name,' . $id,
                'code' => 'required|string|max:10|unique:departments,code,' . $id,
                'description' => 'nullable|string|max:500',
            ]);

            $department = Department::findOrFail($id);
            $department->update($validated);

            Log::channel('custom')->info('Department updated successfully: ' . $validated['name']);

            return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating department: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update department.'])->withInput();
        } finally {
            Log::channel('custom')->info('Update method executed for department');
        }
    }


    /**
     * Update the status of the specified department.
     */
    public function updateStatus(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $validated = $request->validate([
                'status' => 'required|in:active,inactive',
                'reason' => 'nullable|string|max:500',
            ]);

            $department = Department::findOrFail($id);
            $department->update(['status' => $validated['status']]);

            $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

            Log::channel('custom')->info('Department status updated: ' . $department->name . ' ' . $statusMessage);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Department {$statusMessage} successfully.",
                    'status' => $validated['status']
                ]);
            }

            return redirect()->route('admin.departments.index')->with('success', "Department {$statusMessage} successfully.");
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating department status: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
            }
            return back()->withErrors(['error' => 'Failed to update status.']);
        } finally {
            Log::channel('custom')->info('UpdateStatus method executed');
        }
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