<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApplyLeave;
use App\Models\Leave;

class LeaveController extends Controller
{
    /**
     * Get leave balance for the user.
     */
    private function getLeaveBalance($userId, $year)
    {
        return ApplyLeave::getLeaveBalance($userId, $year);
    }

    /**
     * Calculate the number of leave days between two dates.
     */
    private function calculateLeaveDays($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        return $interval->days + 1;
    }

    /**
     * Show leave list page for employees.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? date('Y');
        
        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        
        $appliedLeaves = ApplyLeave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('User.leaves.index-employee', compact('leaveBalance', 'year', 'appliedLeaves'));
    }

    /**
     * Show the apply leave form for employees/managers.
     */
    public function apply(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? date('Y');
        
        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        
        $appliedLeaves = ApplyLeave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('User.leaves.apply', compact('leaveBalance', 'year', 'appliedLeaves'));
    }

    /**
     * Store a newly created leave application.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $year = date('Y');
        
        $request->validate([
            'leave_type' => 'required|in:sick,casual,earned',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'is_half_day' => 'nullable|boolean',
            'half_day_period' => 'required_if:is_half_day,1|in:morning,afternoon',
        ]);

        $leaveDays = $this->calculateLeaveDays($request->start_date, $request->end_date);
        
        if ($request->is_half_day) {
            $leaveDays = 0.5;
        }

        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        
        $availableLeaves = $leaveBalance[$request->leave_type] ?? 0;
        
        if ($leaveDays > $availableLeaves) {
            return back()->with('error', 'Insufficient leave balance. You have ' . $availableLeaves . ' ' . $request->leave_type . ' leaves available.');
        }

        ApplyLeave::create([
            'user_id' => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'leave_days' => $leaveDays,
            'reason' => $request->reason,
            'status' => 'pending',
            'is_half_day' => $request->is_half_day ?? false,
            'half_day_period' => $request->half_day_period ?? null,
        ]);

        return redirect()->route('employee.leaves.index')->with('success', 'Leave application submitted successfully.');
    }
}
