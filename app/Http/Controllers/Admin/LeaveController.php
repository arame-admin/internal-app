<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\ApplyLeave;
use App\Models\User;
use App\Mail\LeaveApplicationNotification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class LeaveController extends Controller
{
    /**
     * Get a leave by its encrypted ID.
     */
    private function getLeave($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        return Leave::findOrFail($id);
    }

    /**
     * Calculate the number of leave days between two dates.
     */
    private function calculateLeaveDays($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        return $interval->days + 1; // Include both start and end date
    }

    /**
     * Display the leaves list page.
     */
    public function index(Request $request)
    {
        $leaves = Leave::orderBy('year', 'desc')->get()->map(function ($leave) {
            return [
                'id' => $leave->id,
                'year' => $leave->year,
                'sick_leaves' => $leave->sick_leave,
                'casual_leaves' => $leave->casual_leave,
                'earned_leaves' => $leave->earned_leaves,
                'status' => $leave->status,
                'created_at' => $leave->created_at,
            ];
        });
        return view('leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new leave configuration.
     */
    public function create()
    {
        return view('leaves.create');
    }

    /**
     * Store a newly created leave configuration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2025|unique:leaves,year',
            'sick_leaves' => 'required|numeric|min:0|max:99',
            'casual_leaves' => 'required|numeric|min:0|max:99',
            'earned_leaves' => 'required|numeric|min:0|max:99',
        ]);

        Leave::create([
            'year' => $request->year,
            'sick_leave' => $request->sick_leaves,
            'casual_leave' => $request->casual_leaves,
            'earned_leaves' => $request->earned_leaves,
            'status' => 'active',
        ]);

        return redirect()->route('admin.leaves.index')->with('success', 'Leave configuration created successfully.');
    }

    /**
     * Show the form for editing the specified leave configuration.
     */
    public function edit($encryptedId)
    {
        $leave = $this->getLeave($encryptedId);
        $leaveData = [
            'id' => $leave->id,
            'year' => $leave->year,
            'sick_leaves' => $leave->sick_leave,
            'casual_leaves' => $leave->casual_leave,
            'earned_leaves' => $leave->earned_leaves,
            'status' => $leave->status,
            'created_at' => $leave->created_at,
        ];
        return view('leaves.edit', compact('leaveData'));
    }

    /**
     * Update the specified leave configuration.
     */
    public function update(Request $request, $encryptedId)
    {
        $leave = $this->getLeave($encryptedId);

        $request->validate([
            'sick_leaves' => 'required|numeric|min:0|max:99',
            'casual_leaves' => 'required|numeric|min:0|max:99',
            'earned_leaves' => 'required|numeric|min:0|max:99',
        ]);

        $leave->update([
            'sick_leave' => $request->sick_leaves,
            'casual_leave' => $request->casual_leaves,
            'earned_leaves' => $request->earned_leaves,
        ]);

        return redirect()->route('admin.leaves.index')->with('success', 'Leave configuration updated successfully.');
    }

    /**
     * Remove the specified leave configuration.
     */
    public function destroy($encryptedId)
    {
        $leave = $this->getLeave($encryptedId);
        $leave->delete();

        return redirect()->route('admin.leaves.index')->with('success', 'Leave configuration deleted successfully.');
    }

    /**
     * Update status of the specified leave configuration.
     */
    public function updateStatus(Request $request, $encryptedId)
    {
        $leave = $this->getLeave($encryptedId);

        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $leave->update([
            'status' => $request->status,
        ]);

        $statusMessage = $request->status === 'active' ? 'activated' : 'deactivated';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Leave configuration {$statusMessage} successfully.",
                'status' => $request->status
            ]);
        }

        return redirect()->route('admin.leaves.index')->with('success', "Leave configuration {$statusMessage} successfully.");
    }

    /**
     * Show the apply leave form for employees/managers.
     */
    public function apply(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? date('Y');
        
        // Get leave balance for the user
        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        
        // Get all applied leaves for the user
        $appliedLeaves = ApplyLeave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('leaves.apply', compact('leaveBalance', 'year', 'appliedLeaves'));
    }

    /**
     * Show leave list page for employees.
     */
    public function indexEmployee(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? date('Y');
        
        // Get leave balance for the user
        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        
        // Get all applied leaves for the user
        $appliedLeaves = ApplyLeave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('leaves.index-employee', compact('leaveBalance', 'year', 'appliedLeaves'));
    }

    /**
     * Store a leave application submitted by employee/manager.
     */
    public function storeApplication(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:sick_leave,casual_leave,earned_leave',
            'year' => 'required|integer|min:2025',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        // Validate that start and end dates are not weekends
        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);
        
        $startDay = $startDate->format('N'); // 1 (Monday) to 7 (Sunday)
        $endDay = $endDate->format('N');
        
        if ($startDay >= 6) {
            return redirect()->back()->with('error', 'Start date cannot be on a weekend. Please select a weekday.')->withInput();
        }
        if ($endDay >= 6) {
            return redirect()->back()->with('error', 'End date cannot be on a weekend. Please select a weekday.')->withInput();
        }

        $user = auth()->user();
        $year = $request->year;
        $totalDays = $this->calculateLeaveDays($request->start_date, $request->end_date);
        
        // Get leave balance
        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        
        // Map leave type to balance key
        $leaveTypeMap = [
            'sick_leave' => 'sick_leave_balance',
            'casual_leave' => 'casual_leave_balance',
            'earned_leave' => 'earned_leave_balance',
        ];
        
        $balanceKey = $leaveTypeMap[$request->leave_type];
        $availableBalance = $leaveBalance[$balanceKey] ?? 0;
        
        // Check if user has sufficient leave balance
        if ($totalDays > $availableBalance) {
            return redirect()->back()->with('error', 'Insufficient leave balance. You have ' . $availableBalance . ' days available.');
        }

        // Create leave application
        $applyLeave = ApplyLeave::create([
            'user_id' => $user->id,
            'leave_type' => $request->leave_type,
            'year' => $year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Send email notification to reporting manager
        if ($user->reportingManager) {
            try {
                Mail::to($user->reportingManager->email)->send(
                    new LeaveApplicationNotification($applyLeave, $user, $user->reportingManager)
                );
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::error('Failed to send leave application email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Leave application submitted successfully and notification sent to your reporting manager.');
    }

    /**
     * Show the leave approval page for managers.
     */
    public function approve()
    {
        $user = auth()->user();
        
        // Get all leave applications from subordinates
        $subordinateIds = $user->subordinates()->pluck('users.id');
        
        $pendingLeaves = ApplyLeave::with(['user', 'user.department'])
            ->whereIn('user_id', $subordinateIds)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('leaves.approve', compact('pendingLeaves'));
    }

    /**
     * Update the status of a leave application (approve/reject).
     */
    public function approveUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:500|nullable',
        ]);

        $applyLeave = ApplyLeave::findOrFail($id);
        $approver = auth()->user();

        $applyLeave->update([
            'status' => $request->status,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        $statusMessage = $request->status === 'approved' ? 'approved' : 'rejected';
        
        return redirect()->route('manager.leaves.approve')->with('success', "Leave application {$statusMessage} successfully.");
    }
}
