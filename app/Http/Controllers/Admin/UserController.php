<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Designation;
use App\Models\BusinessUnit;
use App\Models\Location;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

/**
 * UserController handles CRUD operations for users.
 *
 * This controller provides methods to manage users including listing,
 * creating, updating, deleting, and toggling status. It includes validation,
 * logging, and proper error handling.
 *
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    /**
     * Display a listing of users with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'department', 'designation', 'businessUnit', 'location']);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('personal_email', 'like', "%{$search}%")
                  ->orWhere('work_email', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Department filter
        if ($request->has('department') && !empty($request->department)) {
            $query->where('department_id', $request->department);
        }

        // Role filter
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role_id', $request->role);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if ($sort == 'date') {
            $sort = 'created_at';
        }

        $query->orderBy($sort, $direction);

        // Paginate
        $users = $query->paginate(10);

        // Get data for filter dropdowns
        $departments = Department::where('status', 'active')->get();
        $roles = Role::all();

        return view('Admin.users.index', compact('users', 'departments', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $departments = Department::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        $businessUnits = BusinessUnit::where('status', 'active')->get();
        $locations = Location::where('status', 'active')->get();

        // Generate next employee code
        $lastUser = User::orderBy('id', 'desc')->first();
        if ($lastUser && !empty($lastUser->employee_code)) {
            $nextEmployeeCode = intval($lastUser->employee_code) + 1;
        } else {
            $nextEmployeeCode = 1000;
        }

        return view('Admin.users.create', compact('roles', 'departments', 'designations', 'businessUnits', 'locations', 'nextEmployeeCode'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                // Basic Information
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'personal_email' => 'nullable|string|email|max:255|unique:users,personal_email',
                'phone_number' => 'nullable|string|max:20',

                // Personal Details
                'about_me' => 'nullable|string|max:1000',
                'what_i_love_about_job' => 'nullable|string|max:1000',
                'gender' => 'nullable|in:male,female,other',
                'dob' => 'nullable|date|before:today',
                'marital_status' => 'nullable|in:single,married,divorced,widowed',
                'marriage_date' => 'nullable|date|before_or_equal:today',
                'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'physically_handicapped' => 'boolean',
                'nationality' => 'nullable|string|max:100',

                // Work Information
                'work_email' => 'nullable|string|email|max:255|unique:users,work_email',
                'work_number' => 'nullable|string|max:20',
                'residence_number' => 'nullable|string|max:20',
                'current_address' => 'nullable|string|max:500',
                'permanent_address' => 'nullable|string|max:500',
                'employee_code' => 'nullable|string|max:50|unique:users,employee_code',
                'date_of_joining' => 'nullable|date|before_or_equal:today',
                'job_title' => 'nullable|string|max:255',

                // Relationships
                'role_id' => 'required|exists:roles,id',
                'department_id' => 'nullable|exists:departments,id',
                'designation_id' => 'nullable|exists:designations,id',
                'bu_id' => 'nullable|exists:business_units,id',
                'location_id' => 'nullable|exists:locations,id',
            ]);

            // Set default values
            $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];
            $validated['is_active'] = true;

            User::create($validated);

            Log::channel('custom')->info('User created successfully: ' . $validated['name']);

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error creating user: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create user.'])->withInput();
        }
    }

    /**
     * Show the form for editing a user.
     */
    public function edit($encryptedId)
    {
        $user = User::findOrFail(Crypt::decrypt($encryptedId));

        $roles = Role::all();
        $departments = Department::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        $businessUnits = BusinessUnit::where('status', 'active')->get();
        $locations = Location::where('status', 'active')->get();

        return view('Admin.users.edit', compact('user', 'roles', 'departments', 'designations', 'businessUnits', 'locations'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $encryptedId)
    {
        try {
            $user = User::findOrFail(Crypt::decrypt($encryptedId));

            // Validation
            $validated = $request->validate([
                // Basic Information
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'personal_email' => 'nullable|string|email|max:255|unique:users,personal_email,' . $user->id,
                'phone_country_code' => 'nullable|string|max:5',
                'phone_number' => 'nullable|string|max:20',

                // Personal Details
                'about_me' => 'nullable|string|max:1000',
                'what_i_love_about_job' => 'nullable|string|max:1000',
                'gender' => 'nullable|in:male,female,other',
                'dob' => 'nullable|date|before:today',
                'marital_status' => 'nullable|in:single,married,divorced,widowed',
                'marriage_date' => 'nullable|date|before_or_equal:today',
                'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'physically_handicapped' => 'boolean',
                'nationality' => 'nullable|string|max:100',

                // Work Information
                'work_email' => 'nullable|string|email|max:255|unique:users,work_email,' . $user->id,
                'work_number' => 'nullable|string|max:20',
                'residence_number' => 'nullable|string|max:20',
                'current_address' => 'nullable|string|max:500',
                'permanent_address' => 'nullable|string|max:500',
                'employee_code' => 'nullable|string|max:50|unique:users,employee_code,' . $user->id,
                'date_of_joining' => 'nullable|date|before_or_equal:today',
                'job_title' => 'nullable|string|max:255',

                // Relationships
                'role_id' => 'required|exists:roles,id',
                'department_id' => 'nullable|exists:departments,id',
                'designation_id' => 'nullable|exists:designations,id',
                'bu_id' => 'nullable|exists:business_units,id',
                'location_id' => 'nullable|exists:locations,id',
            ]);

            // Update name
            $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];

            $user->update($validated);

            Log::channel('custom')->info('User updated successfully: ' . $validated['name']);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating user: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update user.'])->withInput();
        }
    }

    /**
     * Update the status of the specified user.
     */
    public function updateStatus(Request $request, $encryptedId)
    {
        try {
            $user = User::findOrFail(Crypt::decrypt($encryptedId));

            $validated = $request->validate([
                'status' => 'required|in:active,inactive',
                'reason' => 'nullable|string|max:500',
            ]);

            $user->update(['is_active' => $validated['status'] === 'active']);

            $statusMessage = $validated['status'] === 'active' ? 'activated' : 'deactivated';

            Log::channel('custom')->info('User status updated: ' . $user->name . ' ' . $statusMessage);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "User {$statusMessage} successfully.",
                    'status' => $validated['status']
                ]);
            }

            return redirect()->route('admin.users.index')->with('success', "User {$statusMessage} successfully.");
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating user status: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
            }
            return back()->withErrors(['error' => 'Failed to update status.']);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy($encryptedId)
    {
        try {
            $user = User::findOrFail(Crypt::decrypt($encryptedId));
            $user->delete();

            Log::channel('custom')->info('User deleted successfully: ' . $user->name);

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error deleting user: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete user.']);
        }
    }

    /**
     * Show the form for editing user payroll.
     */
    public function editPayroll($encryptedId)
    {
        $user = User::with(['role', 'department'])->findOrFail(Crypt::decrypt($encryptedId));

        // For now, using sample payroll data - in real implementation, this would come from a payroll table
        $payrollHistory = [
            [
                'date' => '2024-01-01',
                'basic_salary' => 50000,
                'hra' => 10000,
                'conveyance' => 19200,
                'medical' => 5000,
                'total' => 84200,
                'updated_by' => 'Admin User'
            ],
            [
                'date' => '2023-07-01',
                'basic_salary' => 45000,
                'hra' => 9000,
                'conveyance' => 19200,
                'medical' => 5000,
                'total' => 78200,
                'updated_by' => 'HR Manager'
            ],
            [
                'date' => '2023-01-01',
                'basic_salary' => 40000,
                'hra' => 8000,
                'conveyance' => 19200,
                'medical' => 5000,
                'total' => 72200,
                'updated_by' => 'Admin User'
            ]
        ];

        return view('Admin.users.payroll', compact('user', 'payrollHistory'));
    }

    /**
     * Update the payroll information for the specified user.
     */
    public function updatePayroll(Request $request, $encryptedId)
    {
        try {
            $user = User::findOrFail(Crypt::decrypt($encryptedId));

            $validated = $request->validate([
                'basic_salary' => 'nullable|numeric|min:0',
                'hra' => 'nullable|numeric|min:0',
                'conveyance' => 'nullable|numeric|min:0',
                'medical' => 'nullable|numeric|min:0',
            ]);

            // TODO: In real implementation, save to payroll table
            // For now, just log the action
            Log::channel('custom')->info('Payroll updated for user: ' . $user->name);

            return redirect()->route('admin.users.index')->with('success', 'Payroll updated successfully.');
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error updating payroll: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update payroll.']);
        }
    }
}

