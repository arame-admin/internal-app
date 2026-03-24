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
        
        // Get timesheets for subordinates (exclude draft status for managers/admins)
        $query = Timesheet::with('user.department', 'user.designation')
            ->whereIn('user_id', $subordinateIds)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', '!=', 'draft');
        
        if ($status !== 'all' && $status !== 'draft') {
            $query->where('status', $status);
        }
        
        $timesheets = $query->orderBy('date', 'desc')->orderBy('user_id')->get();
        
        // Calculate team totals
        $totalHours = $timesheets->sum('hours');
        $approvedHours = $timesheets->where('status', 'approved')->sum('hours');
        $pendingHours = $timesheets->where('status', 'pending')->sum('hours');
        
        // Group by user for summary
        $userSummary = $timesheets->groupBy('user_id')->map(function ($userTimesheets) {
            return [
                'total_hours' => $userTimesheets->sum('hours'),
                'approved_hours' => $userTimesheets->where('status', 'approved')->sum('hours'),
                'pending_hours' => $userTimesheets->where('status', 'pending')->sum('hours'),
                'count' => $userTimesheets->count(),
            ];
        });
        
        // Group by date for detailed view
        $dateGroupedTimesheets = $timesheets->groupBy(function($item) {
            return $item->date->format('Y-m-d');
        });
        
        return view('User.manager.team-timesheets', compact(
            'subordinates', 'timesheets', 'year', 'month', 'status',
            'totalHours', 'approvedHours', 'pendingHours', 'userSummary',
            'dateGroupedTimesheets'
        ));
    }

    /**
     * Show detailed timesheets for a specific team member.
     */
    public function teamTimesheetDetail(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        $userId = $request->user_id;
        
        // Verify the user is a subordinate
        $subordinateIds = $user->subordinates()->pluck('id');
        if (!$subordinateIds->contains($userId)) {
            abort(403, 'Unauthorized access to this employee timesheet');
        }
        
        $subordinate = User::with('department')->findOrFail($userId);
        
        $timesheets = Timesheet::with('project')
            ->where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', '!=', 'draft')
            ->orderBy('date', 'desc')
            ->get();
        
        // Group by date
        $groupedTimesheets = $timesheets->groupBy(function($item) {
            return $item->date->format('Y-m-d');
        });
        
        // Calculate hours by status
        $totalHours = $timesheets->sum('hours'); // All hours (regardless of status)
        $approvedHours = $timesheets->where('status', 'approved')->sum('hours');
        $pendingHours = $timesheets->where('status', 'pending')->sum('hours');
        $rejectedHours = $timesheets->where('status', 'rejected')->sum('hours');
        
        return view('User.manager.team-timesheet-detail', compact(
            'subordinate', 'timesheets', 'groupedTimesheets', 'year', 'month',
            'totalHours', 'approvedHours', 'pendingHours', 'rejectedHours'
        ));
    }

    /**
     * Show timesheet approval page for manager.
     * Shows timesheets grouped by date with entries for that date.
     */
    public function approveTimesheet()
    {
        $user = auth()->user();
        $pending = Timesheet::pendingForUser($user);
        
        // Group by date and user (single submission per date per user)
        $groupedTimesheets = $pending->groupBy(function($item) {
            return $item->date->format('Y-m-d') . '-' . $item->user_id;
        });

        return view('User.timesheets.approve', compact('groupedTimesheets', 'pending'));
    }

    /**
     * Approve all timesheets for a specific date and user (single submission).
     * Only updates pending timesheets - previously approved ones are left unchanged.
     */
    public function approveByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'user_id' => 'required|integer',
            'status' => 'required|in:approved,rejected,pending',
            'rejection_reason' => 'required_if:status,rejected|string|max:500|nullable',
        ]);

        $approver = auth()->user();
        
        // Get only pending timesheets for this date and user
        // Previously approved timesheets are left unchanged
        $timesheets = Timesheet::where('date', $request->date)
            ->where('user_id', $request->user_id)
            ->where('status', 'pending')
            ->get();

        if ($timesheets->isEmpty()) {
            return redirect()->route('manager.timesheets.approve')->with('error', 'No pending timesheets found for this date.');
        }

        foreach ($timesheets as $timesheet) {
            $timesheet->update([
                'status' => $request->status,
                'approved_by' => $request->status === 'approved' ? $approver->id : null,
                'rejection_reason' => $request->rejection_reason,
            ]);
        }

        $count = $timesheets->count();
        $statusMsg = $request->status === 'approved' ? 'approved' : ($request->status === 'pending' ? 'reset to pending' : 'rejected');
        return redirect()->route('manager.timesheets.approve')->with('success', "{$count} timesheet(s) for {$request->date} {$statusMsg}.");
    }

    /**
     * Update timesheet status (approve/reject).
     * Approves/Rejects all entries in the same batch together.
     */
    public function updateTimesheet(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:500|nullable',
        ]);

        $timesheet = Timesheet::findOrFail($id);
        $approver = auth()->user();

        // If this entry has a batch_id, update all entries in the batch
        if ($timesheet->batch_id) {
            Timesheet::where('batch_id', $timesheet->batch_id)
                ->update([
                    'status' => $request->status,
                    'approved_by' => $request->status === 'approved' ? $approver->id : null,
                    'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
                ]);
        } else {
            $timesheet->update([
                'status' => $request->status,
                'approved_by' => $request->status === 'approved' ? $approver->id : null,
                'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            ]);
        }

        $statusMsg = $request->status === 'approved' ? 'approved' : 'rejected';
        
        // Get the timesheet to find the user_id for redirect
        $timesheet = Timesheet::find($id);
        $redirectRoute = 'manager.timesheets.team.detail';
        $redirectParams = [
            'user_id' => $timesheet->user_id,
            'year' => $request->year ?? now()->year,
            'month' => $request->month ?? now()->month,
        ];
        
        return redirect()->route($redirectRoute, $redirectParams)->with('success', "Timesheet {$statusMsg}.");
    }
}
