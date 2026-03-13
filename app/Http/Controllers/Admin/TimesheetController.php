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
        
        return view('timesheets.index-employee', compact('timesheets', 'year', 'month', 'monthlyTotal'));
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
        
        return view('timesheets.apply', compact('year', 'month', 'monthlyTotal', 'existing'));
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

        return view('timesheets.approve', compact('pending'));
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
}
?>

