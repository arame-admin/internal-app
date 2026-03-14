<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApplyLeave;
use App\Models\Timesheet;

class ManagerController extends Controller
{
    /**
     * Show manager dashboard.
     */
    public function dashboard()
    {
        return view('User.manager.dashboard');
    }

    /**
     * Show leave approval page for manager.
     */
    public function approveLeave()
    {
        $user = auth()->user();
        
        $pendingLeaves = ApplyLeave::whereHas('user', function ($query) use ($user) {
            $query->where('reporting_manager', $user->id);
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
            'rejection_reason' => $request->rejection_reason,
        ]);

        $statusMsg = $request->status === 'approved' ? 'approved' : 'rejected';
        return redirect()->route('manager.leaves.approve')->with('success', "Leave application {$statusMsg}.");
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
            'rejection_reason' => $request->rejection_reason,
        ]);

        $statusMsg = $request->status === 'approved' ? 'approved' : 'rejected';
        return redirect()->route('manager.timesheets.approve')->with('success', "Timesheet {$statusMsg}.");
    }
}
