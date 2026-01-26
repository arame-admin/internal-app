<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * BusinessUnitController handles CRUD operations for business units.
 *
 * This controller provides methods to manage business units including listing,
 * creating, updating, deleting, and toggling status. It includes validation,
 * logging, and proper error handling.
 *
 * @package App\Http\Controllers\Admin
 */
class BusinessUnitController extends Controller
{
    /**
     * Get a business unit by its encrypted ID.
     *
     * @param string $encryptedId The encrypted business unit ID
     * @return BusinessUnit The business unit model instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If business unit not found
     */
    private function getBusinessUnit($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        return BusinessUnit::findOrFail($id);
    }

    /**
     * Display a listing of business units with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = BusinessUnit::query();

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
        $businessUnits = $query->paginate(10);

        return view('Admin.business-units.index', compact('businessUnits'));
    }

    /**
     * Show the form for creating a new business unit.
     */
    public function create()
    {
        return view('Admin.business-units.create');
    }

    /**
     * Store a newly created business unit.
     *
     * Validates the input data, creates a new business unit with active status,
     * logs the operation, and redirects to the index page.
     *
     * @param Request $request The HTTP request containing business unit data
     * @return \Illuminate\Http\RedirectResponse Redirect to business units index
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:business_units,name',
                'code' => 'required|string|max:10|unique:business_units,code',
                'description' => 'nullable|string|max:500',
            ]);

            $validated['status'] = 'active';

            BusinessUnit::create($validated);

            Log::channel('custom')->info('Business unit created successfully: ' . $validated['name']);

            return redirect()->route('admin.business-units.index')->with('success', 'Business unit created successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error creating business unit: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create business unit.'])->withInput();
        } finally {
            Log::channel('custom')->info('Store method executed for business unit');
        }
    }

    /**
     * Show the form for editing a business unit.
     *
     * @param string $encryptedId The encrypted business unit ID
     * @return \Illuminate\View\View The edit view with business unit data
     */
    public function edit($encryptedId)
    {
        $businessUnit = $this->getBusinessUnit($encryptedId);
        return view('Admin.business-units.edit', compact('businessUnit'));
    }

    /**
     * Update the specified business unit.
     */
    public function update(Request $request, $encryptedId)
    {
        try {
            $businessUnit = $this->getBusinessUnit($encryptedId);
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:business_units,name,' . $businessUnit->id,
                'code' => 'required|string|max:10|unique:business_units,code,' . $businessUnit->id,
                'description' => 'nullable|string|max:500',
            ]);
            $businessUnit->update($validated);

            Log::channel('custom')->info('Business unit updated successfully: ' . $validated['name']);

            return redirect()->route('admin.business-units.index')->with('success', 'Business unit updated successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating business unit: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update business unit.'])->withInput();
        } finally {
            Log::channel('custom')->info('Update method executed for business unit');
        }
    }


    /**
     * Update the status of the specified business unit.
     */
    public function updateStatus(Request $request, $encryptedId)
    {
        try {
            $businessUnit = $this->getBusinessUnit($encryptedId);
            $validated = $request->validate([
                'status' => 'required|in:active,inactive',
                'reason' => 'nullable|string|max:500',
            ]);
            $businessUnit->update(['status' => $validated['status']]);

            $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

            Log::channel('custom')->info('Business unit status updated: ' . $businessUnit->name . ' ' . $statusMessage);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Business unit {$statusMessage} successfully.",
                    'status' => $validated['status']
                ]);
            }

            return redirect()->route('admin.business-units.index')->with('success', "Business unit {$statusMessage} successfully.");
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating business unit status: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
            }
            return back()->withErrors(['error' => 'Failed to update status.']);
        } finally {
            Log::channel('custom')->info('UpdateStatus method executed');
        }
    }

    /**
     * Remove the specified business unit.
     *
     * @param string $encryptedId The encrypted business unit ID
     * @return \Illuminate\Http\RedirectResponse Redirect to business units index
     */
    public function destroy($encryptedId)
    {
        $businessUnit = $this->getBusinessUnit($encryptedId);
        $businessUnit->delete();

        return redirect()->route('admin.business-units.index')->with('success', 'Business unit deleted successfully.');
    }
}