<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApplyLeave;
use App\Models\Department;

class LeaveApplicationsController extends Controller
{
    /**
     * Show admin list of all leave applications.
     */
    public function index(Request $request)
    {
        // Overall counts
        $totalRequests = ApplyLeave::count();
        $pendingCount = ApplyLeave::where('status', 'pending')->count();
        $approvedCount = ApplyLeave::where('status', 'approved')->count();
        $canceledCount = ApplyLeave::where('status', 'canceled')->count();

        $departments = Department::orderBy('name')->get();

        $query = ApplyLeave::with(['user', 'user.department', 'approver'])
            ->filter($request->all());

        $applications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('Admin.leaves.applications-index', compact('applications', 'totalRequests', 'pendingCount', 'approvedCount', 'canceledCount', 'departments'));
    }

    /**
     * Update leave application status for admin.
     */
    public function update(Request $request, $id)
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
        
        return redirect()->route('admin.leave.applications')->with('success', "Leave application {$statusMessage} successfully.");
    }
}
