<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Timesheet;

class TimesheetController extends Controller
{
    /**
     * Display timesheet list for employee.
     */
    public function index(Request $request)
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
            ->where('status', '!=', 'approved')
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
     * Update existing draft entry.
     */
    public function updateDraft(Request $request, $id)
    {
        $timesheet = Timesheet::where('user_id', auth()->id())
            ->where('id', $id)
            ->whereIn('status', ['draft', 'rejected'])
            ->firstOrFail();

        $request->validate([
            'hours' => 'required|numeric|min:0|max:24',
            'description' => 'nullable|string|max:1000',
        ]);

        $timesheet->update([
            'hours' => $request->hours,
            'description' => $request->description,
            'status' => 'draft',
        ]);

        return redirect()->route('employee.timesheets.apply')->with('success', 'Timesheet entry updated successfully.');
    }
}
