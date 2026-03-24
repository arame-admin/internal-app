<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\TimesheetReminder;
use Illuminate\Validation\Rule;

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
        
        // Group entries by batch_id to display them together
        $groupedExisting = $existing->groupBy(function($entry) {
            return $entry->date->format('Y-m-d') . '_' . ($entry->batch_id ?? 'no-batch_' . $entry->id);
        });
        
        $monthlyTotal = Timesheet::monthlyTotal($user->id, $year, $month);
        $weeklyTotal = Timesheet::weeklyTotal($user->id);
        $projects = Project::with('projectDepartment')
            ->where('status', '!=', 'cancelled')
            ->orderBy('name')
            ->get();
        
        // Get user's department's available_tasks for fallback (already array from model cast)
        $userDepartmentTasks = [];
        if ($user->department) {
            $deptTasks = $user->department->available_tasks;
            // Handle both array (from cast) and string (raw DB) cases
            if (is_array($deptTasks) && !empty($deptTasks)) {
                $userDepartmentTasks = $deptTasks;
            } elseif (is_string($deptTasks) && !empty($deptTasks)) {
                $decoded = json_decode($deptTasks, true);
                if (is_array($decoded) && !empty($decoded)) {
                    $userDepartmentTasks = $decoded;
                }
            }
        }
        
        foreach ($projects as $project) {
            // Prioritize project department (non-empty), then user dept, then defaults
            $projectTasks = [];
            if ($project->projectDepartment) {
                $pdTasks = $project->projectDepartment->available_tasks;
                // Handle both array (from cast) and string (raw DB) cases
                if (is_array($pdTasks) && !empty($pdTasks)) {
                    $projectTasks = $pdTasks;
                } elseif (is_string($pdTasks) && !empty($pdTasks)) {
                    $decoded = json_decode($pdTasks, true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $projectTasks = $decoded;
                    }
                }
            }
            if (empty($projectTasks) && !empty($userDepartmentTasks)) {
                $projectTasks = $userDepartmentTasks;
            }
            if (empty($projectTasks)) {
                $projectTasks = ['General Work', 'Meeting', 'Documentation', 'UI/UX', 'Coding', 'Testing', 'DevOps', 'Project Meeting'];
            }
            $project->tasks = $projectTasks;
        }
        
        return view('User.timesheets.apply', compact('year', 'month', 'monthlyTotal', 'weeklyTotal', 'existing', 'projects', 'userDepartmentTasks', 'groupedExisting'));
    }

    /**
     * Store timesheet entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'entries' => 'required|array|min:1',
            'entries.*.start_time' => 'required',
            'entries.*.end_time' => 'required|after:entries.*.start_time',
            'entries.*.project_id' => 'required|exists:projects,id',
            'entries.*.task' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        
        // Check if the date is beyond 48 hours (cannot apply old timesheets)
        $timesheetDate = \Carbon\Carbon::parse($request->date)->startOfDay();
        $cutoffDate = now()->subHours(48)->startOfDay();
        
        if ($timesheetDate->lt($cutoffDate)) {
            return back()->with('error', 'Timesheet cannot be applied for dates older than 48 hours. Please contact your manager or HR for assistance.');
        }

        $entriesCount = 0;
        
        // Generate a unique batch_id for this submission
        $batchId = 'batch_' . time() . '_' . $user->id;
        
        // Process each time entry
        foreach ($request->entries as $entry) {
            // Skip if start_time or end_time is empty
            if (empty($entry['start_time']) || empty($entry['end_time'])) {
                continue;
            }
            
            // Calculate hours from times
            $start = \Carbon\Carbon::createFromFormat('H:i', $entry['start_time']);
            $end = \Carbon\Carbon::createFromFormat('H:i', $entry['end_time']);
            
            if ($end < $start) {
                $end->addDay();
            }
            
            // Calculate hours (no break time deduction)
            $hours = round($start->diffInMinutes($end) / 60, 2);
            
            // Skip if no hours
            if ($hours <= 0) {
                continue;
            }

            Timesheet::create([
                'user_id' => $user->id,
                'batch_id' => $batchId,
                'date' => $request->date,
                'start_time' => $entry['start_time'],
                'end_time' => $entry['end_time'],
                'break_duration' => 0,
                'hours' => $hours,
                'project_id' => $entry['project_id'],
                'task' => $entry['task'],
                'description' => $request->description,
                'status' => 'draft',
            ]);
            
            $entriesCount++;
        }

        if ($entriesCount == 0) {
            return back()->with('error', 'No valid time entries to submit.');
        }

        // Dismiss any reminder for this date if it exists
        TimesheetReminder::where('user_id', $user->id)
            ->where('missed_date', $request->date)
            ->update(['status' => TimesheetReminder::STATUS_DISMISSED]);

        return redirect()->route('employee.timesheets.apply', ['year' => $request->input('year', now()->year), 'month' => $request->input('month', now()->month)])->with('success', 'Timesheet entries created successfully.');
    }

    /**
     * Submit timesheet for approval (draft -> pending).
     * Submits all entries in the same batch together.
     */
    public function submit(Request $request, $id)
    {
        $timesheet = Timesheet::where('user_id', auth()->id())
            ->where('id', $id)
            ->where('status', 'draft')
            ->firstOrFail();

        // If this entry has a batch_id, submit all entries in the batch
        if ($timesheet->batch_id) {
            Timesheet::where('batch_id', $timesheet->batch_id)
                ->where('user_id', auth()->id())
                ->where('status', 'draft')
                ->update(['status' => 'pending']);
        } else {
            $timesheet->update(['status' => 'pending']);
        }

        return redirect()->route('employee.timesheets.apply', ['year' => $timesheet->date->year, 'month' => $timesheet->date->month])->with('success', 'Timesheet submitted for approval.');
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
            'project_id' => 'required|exists:projects,id',
            'task' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Calculate hours from times (no break time)
        $start = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $request->end_time);
        
        if ($end < $start) {
            $end->addDay();
        }
        
        $hours = round($start->diffInMinutes($end) / 60, 2);

        $timesheet->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_duration' => 0,
            'hours' => $hours,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'task' => $request->task,
            'status' => 'draft', // Reset to draft for resubmission
        ]);

        return redirect()->route('employee.timesheets.apply', ['year' => $timesheet->date->year, 'month' => $timesheet->date->month])->with('success', 'Timesheet entry updated successfully. Hours: ' . number_format($hours, 2));
    }
    
    /**
     * Delete timesheet entry (only drafts).
     * Deletes all entries in the same batch together.
     */
    public function destroy($id)
    {
        $timesheet = Timesheet::where('user_id', auth()->id())
            ->where('id', $id)
            ->where('status', 'draft')
            ->firstOrFail();

        $batchId = $timesheet->batch_id;
        $date = $timesheet->date;

        // If this entry has a batch_id, delete all entries in the batch
        if ($batchId) {
            Timesheet::where('batch_id', $batchId)
                ->where('user_id', auth()->id())
                ->where('status', 'draft')
                ->delete();
        } else {
            $timesheet->delete();
        }

        return redirect()->route('employee.timesheets.apply', ['year' => $date->year, 'month' => $date->month])->with('success', 'Timesheet entry deleted successfully.');
    }
}
