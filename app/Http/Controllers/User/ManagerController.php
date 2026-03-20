<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApplyLeave;
use App\Models\Timesheet;
use App\Models\User;

class ManagerController extends Controller
{
    /**
     * Show manager dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get count of pending leave approvals
        $pendingLeaveCount = ApplyLeave::whereHas('user', function ($query) use ($user) {
            $query->where('reporting_manager_id', $user->id);
        })->where('status', 'pending')->count();
        
        // Get count of pending timesheet approvals
        $pendingTimesheetCount = Timesheet::pendingForUser($user)->count();
        
        // Get subordinates count
        $subordinatesCount = $user->subordinates()->count();
        
        return view('User.manager.dashboard', compact('pendingLeaveCount', 'pendingTimesheetCount', 'subordinatesCount'));
    }

    /**
     * Show leave approval page for manager.
     */
    public function approveLeave()
    {
        $user = auth()->user();
        
        $pendingLeaves = ApplyLeave::whereHas('user', function ($query) use ($user) {
            $query->where('reporting_manager_id', $user->id);
        })->where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('User.leaves.approve', compact('pendingLeaves'));
    }

    /**
     * Update leave application status (approve/reject).
     */
    public function updateLeave(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:500|nullable',
        ]);

        $leaveApplication = ApplyLeave::findOrFail($id);
        
        $leaveApplication->update([
            'status' => $request->status,
            'approved_by' => $request->status === 'approved' ? auth()->id() : null,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ]);

        $statusMsg = $request->status === 'approved' ? 'approved' : 'rejected';
        return redirect()->route('manager.leaves.approve')->with('success', "Leave application {$statusMsg}.");
    }

    /**
     * Show timesheet management dashboard for team.
     */
    public function teamTimesheets(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        $status = $request->status ?? 'all';
        
        // Get subordinates
        $subordinates = $user->subordinates()->with('department')->get();
        $subordinateIds = $subordinates->pluck('id');
        
        // Get timesheets for subordinates
        $query = Timesheet::with('user.department', 'user.designation')
            ->whereIn('user_id', $subordinateIds)
            ->whereYear('date', $year)
            ->whereMonth('date', $month);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $timesheets = $query->orderBy('date', 'desc')->orderBy('user_id')->get();
        
        // Calculate team totals
        $totalHours = $timesheets->sum('hours');
        $approvedHours = $timesheets->where('status', 'approved')->sum('hours');
        $pendingHours = $timesheets->where('status', 'pending')->sum('hours');
        $draftHours = $timesheets->whereIn('status', ['draft', 'rejected'])->sum('hours');
        
        // Group by user for summary
        $userSummary = $timesheets->groupBy('user_id')->map(function ($userTimesheets) {
            return [
                'total_hours' => $userTimesheets->sum('hours'),
                'approved_hours' => $userTimesheets->where('status', 'approved')->sum('hours'),
                'pending_hours' => $userTimesheets->where('status', 'pending')->sum('hours'),
                'count' => $userTimesheets->count(),
            ];
        });
        
        return view('User.manager.team-timesheets', compact(
            'subordinates', 'timesheets', 'year', 'month', 'status',
            'totalHours', 'approvedHours', 'pendingHours', 'draftHours', 'userSummary'
        ));
    }

    /**
     * Show timesheet approval page for manager.
     */
    public function approveTimesheet()
    {
        $user = auth()->user();
        $pending = Timesheet::pendingForUser($user);

        return view('User.timesheets.approve', compact('pending'));
    }

    /**
     * Update timesheet status (approve/reject).
     */
    public function updateTimesheet(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:500|nullable',
        ]);

        $timesheet = Timesheet::findOrFail($id);
        $approver = auth()->user();

        $timesheet->update([
            'status' => $request->status,
            'approved_by' => $request->status === 'approved' ? $approver->id : null,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ]);

        $statusMsg = $request->status === 'approved' ? 'approved' : 'rejected';
        return redirect()->route('manager.timesheets.approve')->with('success', "Timesheet {$statusMsg}.");
    }
}
