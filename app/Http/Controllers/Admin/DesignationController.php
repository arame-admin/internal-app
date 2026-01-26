<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * DesignationController handles CRUD operations for designations.
 *
 * This controller provides methods to manage designations including listing,
 * creating, updating, deleting, and toggling status. It includes validation,
 * logging, and proper error handling.
 *
 * @package App\Http\Controllers\Admin
 */
class DesignationController extends Controller
{
    /**
     * Get a designation by its encrypted ID.
     *
     * @param string $encryptedId The encrypted designation ID
     * @return Designation The designation model instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If designation not found
     */
    private function getDesignation($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        return Designation::findOrFail($id);
    }

    /**
     * Display a listing of designations with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = Designation::query();

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
        $designations = $query->with('department')->paginate(10);

        return view('Admin.designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new designation.
     */
    public function create()
    {
        $departments = \App\Models\Department::where('status', 'active')->get();
        return view('Admin.designations.create', compact('departments'));
    }

    /**
     * Store a newly created designation.
     *
     * Validates the input data, creates a new designation with active status,
     * logs the operation, and redirects to the index page.
     *
     * @param Request $request The HTTP request containing designation data
     * @return \Illuminate\Http\RedirectResponse Redirect to designations index
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:designations,name',
                'code' => 'required|string|max:10|unique:designations,code',
                'description' => 'nullable|string|max:500',
                'department_id' => 'required|exists:departments,id',
            ]);

            $validated['status'] = 'active';

            Designation::create($validated);

            Log::channel('custom')->info('Designation created successfully: ' . $validated['name']);

            return redirect()->route('admin.designations.index')->with('success', 'Designation created successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error creating designation: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create designation.'])->withInput();
        } finally {
            Log::channel('custom')->info('Store method executed for designation');
        }
    }

    /**
     * Show the form for editing a designation.
     *
     * @param string $encryptedId The encrypted designation ID
     * @return \Illuminate\View\View The edit view with designation data
     */
    public function edit($encryptedId)
    {
        $designation = $this->getDesignation($encryptedId);
        $departments = \App\Models\Department::where('status', 'active')->get();
        return view('Admin.designations.edit', compact('designation', 'departments'));
    }

    /**
     * Show the form for changing designation status.
     */
    public function showStatus($encryptedId)
    {
        $designation = $this->getDesignation($encryptedId);
        return view('Admin.designations.status', compact('designation'));
    }

    /**
     * Update the specified designation.
     */
    public function update(Request $request, $encryptedId)
    {
        try {
            $designation = $this->getDesignation($encryptedId);
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:designations,name,' . $designation->id,
                'code' => 'required|string|max:10|unique:designations,code,' . $designation->id,
                'description' => 'nullable|string|max:500',
                'department_id' => 'required|exists:departments,id',
            ]);
            $designation->update($validated);

            Log::channel('custom')->info('Designation updated successfully: ' . $validated['name']);

            return redirect()->route('admin.designations.index')->with('success', 'Designation updated successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating designation: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update designation.'])->withInput();
        } finally {
            Log::channel('custom')->info('Update method executed for designation');
        }
    }


    /**
     * Update the status of the specified designation.
     */
    public function updateStatus(Request $request, $encryptedId)
    {
        try {
            $designation = $this->getDesignation($encryptedId);
            $validated = $request->validate([
                'status' => 'required|in:active,inactive',
                'reason' => 'nullable|string|max:500',
            ]);
            $designation->update(['status' => $validated['status']]);

            $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

            Log::channel('custom')->info('Designation status updated: ' . $designation->name . ' ' . $statusMessage);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Designation {$statusMessage} successfully.",
                    'status' => $validated['status']
                ]);
            }

            return redirect()->route('admin.designations.index')->with('success', "Designation {$statusMessage} successfully.");
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating designation status: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
            }
            return back()->withErrors(['error' => 'Failed to update status.']);
        } finally {
            Log::channel('custom')->info('UpdateStatus method executed');
        }
    }

    /**
     * Remove the specified designation.
     *
     * @param string $encryptedId The encrypted designation ID
     * @return \Illuminate\Http\RedirectResponse Redirect to designations index
     */
    public function destroy($encryptedId)
    {
        $designation = $this->getDesignation($encryptedId);
        $designation->delete();

        return redirect()->route('admin.designations.index')->with('success', 'Designation deleted successfully.');
    }
}