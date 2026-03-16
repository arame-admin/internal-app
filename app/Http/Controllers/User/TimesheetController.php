<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use App\Models\TimesheetReminder;

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
            ->whereIn('status', ['draft', 'pending', 'rejected'])
            ->orderBy('date', 'desc')
            ->get();
        
        $monthlyTotal = Timesheet::monthlyTotal($user->id, $year, $month);
        $weeklyTotal = Timesheet::weeklyTotal($user->id);
        
        return view('User.timesheets.apply', compact('year', 'month', 'monthlyTotal', 'weeklyTotal', 'existing'));
    }

    /**
     * Store timesheet entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:timesheets,date,NULL,id,user_id,' . auth()->id(),
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'break_duration' => 'nullable|numeric|min:0|max:4',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        
        // Check if the date is beyond 48 hours (cannot apply old timesheets)
        $timesheetDate = \Carbon\Carbon::parse($request->date)->startOfDay();
        $cutoffDate = now()->subHours(48)->startOfDay();
        
        if ($timesheetDate->lt($cutoffDate)) {
            return back()->with('error', 'Timesheet cannot be applied for dates older than 48 hours. Please contact your manager or HR for assistance.');
        }
        
        // Calculate hours from times
        $start = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
        
        if ($end < $start) {
            $end->addDay();
        }
        
        $totalMinutes = $start->diffInMinutes($end);
        $breakMinutes = ($request->break_duration ?? 0) * 60;
        $workingMinutes = $totalMinutes - $breakMinutes;
        $hours = round($workingMinutes / 60, 2);
        
        // Validate minimum daily hours
        if ($hours < 6.5) {
            return back()->with('error', 'Minimum 6.5 hours required per day (excluding break).');
        }
        
        // Validate weekly hours
        $weeklyTotal = Timesheet::weeklyTotal($user->id);
        $newWeeklyTotal = $weeklyTotal + $hours;
        if ($newWeeklyTotal < 40 && $weeklyTotal > 0) {
            // Warning only, allow submission
            session()->flash('warning', 'Warning: Weekly total will be ' . number_format($newWeeklyTotal, 2) . ' hours. Minimum 40 hours per week required.');
        }

        Timesheet::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_duration' => $request->break_duration ?? 0,
            'hours' => $hours,
            'description' => $request->description,
            'status' => 'draft',
        ]);

        // Dismiss any reminder for this date if it exists
        TimesheetReminder::where('user_id', $user->id)
            ->where('missed_date', $request->date)
            ->update(['status' => TimesheetReminder::STATUS_DISMISSED]);

        return redirect()->route('employee.timesheets.apply')->with('success', 'Timesheet entry created successfully. Hours: ' . number_format($hours, 2));
    }

    /**
     * Submit timesheet for approval (draft -> pending).
     */
    public function submit(Request $request, $id)
    {
        $timesheet = Timesheet::where('user_id', auth()->id())
            ->where('id', $id)
            ->where('status', 'draft')
            ->firstOrFail();

        $timesheet->update(['status' => 'pending']);

        return redirect()->route('employee.timesheets.apply')->with('success', 'Timesheet submitted for approval.');
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
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'break_duration' => 'nullable|numeric|min:0|max:4',
            'description' => 'nullable|string|max:1000',
        ]);

        // Calculate hours from times
        $start = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
        
        if ($end < $start) {
            $end->addDay();
        }
        
        $totalMinutes = $start->diffInMinutes($end);
        $breakMinutes = ($request->break_duration ?? 0) * 60;
        $workingMinutes = $totalMinutes - $breakMinutes;
        $hours = round($workingMinutes / 60, 2);
        
        // Validate minimum daily hours
        if ($hours < 6.5) {
            return back()->with('error', 'Minimum 6.5 hours required per day (excluding break).');
        }

        $timesheet->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_duration' => $request->break_duration ?? 0,
            'hours' => $hours,
            'description' => $request->description,
            'status' => 'draft', // Reset to draft for resubmission
        ]);

        return redirect()->route('employee.timesheets.apply')->with('success', 'Timesheet entry updated successfully. Hours: ' . number_format($hours, 2));
    }
    
    /**
     * Delete timesheet entry (only drafts).
     */
    public function destroy($id)
    {
        $timesheet = Timesheet::where('user_id', auth()->id())
            ->where('id', $id)
            ->where('status', 'draft')
            ->firstOrFail();

        $timesheet->delete();

        return redirect()->route('employee.timesheets.apply')->with('success', 'Timesheet entry deleted successfully.');
    }
}
