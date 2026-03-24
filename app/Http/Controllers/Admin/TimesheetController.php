<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class TimesheetController extends Controller
{
    /**
     * Display timesheet list for employee.
     */
    public function indexEmployee(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        
        $timesheets = Timesheet::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();
        
        $monthlyTotal = Timesheet::monthlyTotal($user->id, $year, $month);
        
        return view('User.timesheets.index-employee', compact('timesheets', 'year', 'month', 'monthlyTotal'));
    }

    /**
     * Show form to log timesheet entry.
     */
    public function apply(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        
        $existing = Timesheet::where('user_id', $user->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', '!=', 'approved') // editable drafts/pending
            ->orderBy('date', 'desc')
            ->get();
        
        $monthlyTotal = Timesheet::monthlyTotal($user->id, $year, $month);
        
        return view('User.timesheets.apply', compact('year', 'month', 'monthlyTotal', 'existing'));
    }

    /**
     * Store timesheet entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:timesheets,date,NULL,id,user_id,' . auth()->id(),
            'hours' => 'required|numeric|min:0|max:24',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        Timesheet::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'hours' => $request->hours,
            'description' => $request->description,
            'status' => 'draft',
        ]);

        return redirect()->route('employee.timesheets.apply')->with('success', 'Timesheet entry created successfully.');
    }

    /**
     * Show approval page for manager.
     */
    public function approve()
    {
        $user = auth()->user();
        $pending = Timesheet::pendingForUser($user);

        return view('User.timesheets.approve', compact('pending'));
    }

    /**
     * Update timesheet status (approve/reject).
     */
    public function approveUpdate(Request $request, $id)
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
        ]);

        if ($request->status === 'rejected') {
            // Could add rejection_reason field if needed
        }

        $statusMsg = $request->status === 'approved' ? 'approved' : 'rejected';
        return redirect()->route('manager.timesheets.approve')->with('success', "Timesheet {$statusMsg}.");
    }

    /**
     * Update existing draft entry.
     */
    public function updateDraft(Request $request, $id)
    {
        $timesheet = Timesheet::where('user_id', auth()->id())
            ->where('id', $id)
            ->whereIn('status', ['draft', 'pending'])
            ->firstOrFail();

        $request->validate([
            'hours' => 'required|numeric|min:0|max:24',
            'description' => 'nullable|string|max:1000',
        ]);

        $timesheet->update($request->only('hours', 'description'));

        if ($timesheet->status === 'draft') {
            $timesheet->update(['status' => 'pending']);
        }

        return redirect()->route('employee.timesheets.apply')->with('success', 'Timesheet updated and submitted for approval.');
    }
    
    // ========== Admin-specific Methods ==========
    
    /**
     * Display all timesheets across the organization (Admin).
     * Grouped by date and employee.
     */
    public function adminIndex(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        $status = $request->status ?? 'all';
        $userId = $request->user_id ?? null;
        
        $query = Timesheet::with('user.department', 'project')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', '!=', 'draft');
        
        if ($status !== 'all' && $status !== 'draft') {
            $query->where('status', $status);
        }
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $timesheets = $query->orderBy('date', 'desc')->orderBy('user_id')->get();
        
        // Group by user for summary
        $userSummary = $timesheets->groupBy('user_id')->map(function ($userTimesheets) {
            return [
                'total_hours' => $userTimesheets->sum('hours'),
                'approved_hours' => $userTimesheets->where('status', 'approved')->sum('hours'),
                'pending_hours' => $userTimesheets->where('status', 'pending')->sum('hours'),
                'count' => $userTimesheets->count(),
            ];
        });
        
        // Get all users for filter
        $users = User::orderBy('name')->get();
        
        // Calculate totals
        $totalHours = $timesheets->sum('hours');
        $approvedHours = $timesheets->where('status', 'approved')->sum('hours');
        $pendingHours = $timesheets->where('status', 'pending')->sum('hours');
        
        return view('Admin.timesheets.index', compact('timesheets', 'userSummary', 'year', 'month', 'status', 'userId', 'users', 'totalHours', 'approvedHours', 'pendingHours'));
    }

    /**
     * Show detailed timesheets for a specific employee.
     */
    public function adminDetail(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        $userId = $request->user_id;
        
        $user = User::with('department')->findOrFail($userId);
        
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
        
        $totalHours = $timesheets->sum('hours');
        $approvedHours = $timesheets->where('status', 'approved')->sum('hours');
        $pendingHours = $timesheets->where('status', 'pending')->sum('hours');
        
        return view('Admin.timesheets.detail', compact('user', 'timesheets', 'groupedTimesheets', 'year', 'month', 'totalHours', 'approvedHours', 'pendingHours'));
    }
    
    /**
     * Show approval page for admin.
     * Shows timesheets grouped by date with entries for that date.
     */
    public function adminApprove(Request $request)
    {
        $status = $request->status ?? 'pending';
        
        // Get timesheets with user and department (exclude draft for admin view)
        $query = Timesheet::with('user.department', 'project')
            ->where('status', '!=', 'draft');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $timesheets = $query->orderBy('date', 'desc')->orderBy('user_id')->get();
        
        // Group by date and user (single submission per date per user)
        $groupedTimesheets = $timesheets->groupBy(function($item) {
            return $item->date->format('Y-m-d') . '-' . $item->user_id;
        });
        
        return view('Admin.timesheets.approve', compact('groupedTimesheets', 'timesheets', 'status'));
    }

    /**
     * Show single timesheet for approval.
     * Displays details of a single timesheet entry for a specific date.
     */
    public function showForApproval($id)
    {
        $timesheet = Timesheet::with('user.department', 'project', 'approver')->findOrFail($id);
        
        return view('Admin.timesheets.approve-show', compact('timesheet'));
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
            return redirect()->route('admin.timesheets.approve')->with('error', 'No pending timesheets found for this date.');
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
        return redirect()->route('admin.timesheets.approve')->with('success', "{$count} timesheet(s) for {$request->date} {$statusMsg}.");
    }
    
    /**
     * Update timesheet status (approve/reject) for admin.
     * Handles single timesheet entry approval.
     */
    public function adminApproveUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'rejection_reason' => 'required_if:status,rejected|string|max:500|nullable',
        ]);

        $timesheet = Timesheet::findOrFail($id);
        $approver = auth()->user();

        $timesheet->update([
            'status' => $request->status,
            'approved_by' => $request->status === 'approved' ? $approver->id : null,
            'rejection_reason' => $request->rejection_reason,
        ]);

        $statusMsg = $request->status === 'approved' ? 'approved' : ($request->status === 'pending' ? 'reset to pending' : 'rejected');
        return redirect()->route('admin.timesheets.approve')->with('success', "Timesheet {$statusMsg}.");
    }
}
?>

