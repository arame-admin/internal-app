<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * LocationController handles CRUD operations for locations.
 *
 * This controller provides methods to manage locations including listing,
 * creating, updating, deleting, and toggling status. It includes validation,
 * logging, and proper error handling.
 *
 * @package App\Http\Controllers\Admin
 */
class LocationController extends Controller
{
    /**
     * Get a location by its encrypted ID.
     *
     * @param string $encryptedId The encrypted location ID
     * @return Location The location model instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If location not found
     */
    private function getLocation($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        return Location::findOrFail($id);
    }

    /**
     * Display a listing of locations with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = Location::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
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
        $locations = $query->paginate(10);

        return view('Admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location.
     */
    public function create()
    {
        return view('Admin.locations.create');
    }

    /**
     * Store a newly created location.
     *
     * Validates the input data, creates a new location with active status,
     * logs the operation, and redirects to the index page.
     *
     * @param Request $request The HTTP request containing location data
     * @return \Illuminate\Http\RedirectResponse Redirect to locations index
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:locations,name',
                'code' => 'required|string|max:10|unique:locations,code',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:500',
            ]);

            $validated['status'] = 'active';

            Location::create($validated);

            Log::channel('custom')->info('Location created successfully: ' . $validated['name']);

            return redirect()->route('admin.locations.index')->with('success', 'Location created successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error creating location: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create location.'])->withInput();
        } finally {
            Log::channel('custom')->info('Store method executed for location');
        }
    }

    /**
     * Show the form for editing a location.
     *
     * @param string $encryptedId The encrypted location ID
     * @return \Illuminate\View\View The edit view with location data
     */
    public function edit($encryptedId)
    {
        $location = $this->getLocation($encryptedId);
        return view('Admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified location.
     */
    public function update(Request $request, $encryptedId)
    {
        try {
            $location = $this->getLocation($encryptedId);
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
                'code' => 'required|string|max:10|unique:locations,code,' . $location->id,
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'description' => 'nullable|string|max:500',
            ]);
            $location->update($validated);

            Log::channel('custom')->info('Location updated successfully: ' . $validated['name']);

            return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating location: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update location.'])->withInput();
        } finally {
            Log::channel('custom')->info('Update method executed for location');
        }
    }


    /**
     * Update the status of the specified location.
     */
    public function updateStatus(Request $request, $encryptedId)
    {
        try {
            $location = $this->getLocation($encryptedId);
            $validated = $request->validate([
                'status' => 'required|in:active,inactive',
                'reason' => 'nullable|string|max:500',
            ]);
            $location->update(['status' => $validated['status']]);

            $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

            Log::channel('custom')->info('Location status updated: ' . $location->name . ' ' . $statusMessage);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Location {$statusMessage} successfully.",
                    'status' => $validated['status']
                ]);
            }

            return redirect()->route('admin.locations.index')->with('success', "Location {$statusMessage} successfully.");
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating location status: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
            }
            return back()->withErrors(['error' => 'Failed to update status.']);
        } finally {
            Log::channel('custom')->info('UpdateStatus method executed');
        }
    }

    /**
     * Remove the specified location.
     *
     * @param string $encryptedId The encrypted location ID
     * @return \Illuminate\Http\RedirectResponse Redirect to locations index
     */
    public function destroy($encryptedId)
    {
        $location = $this->getLocation($encryptedId);
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully.');
    }
}