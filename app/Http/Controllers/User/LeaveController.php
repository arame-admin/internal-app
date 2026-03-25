<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApplyLeave;
use App\Models\Leave;
use App\Models\CompanyHoliday;

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
    private function calculateLeaveDays($startDate, $endDate, $durationType = 'full_day')
    {
        if ($durationType === 'half_day') {
            return 0.5;
        }
        
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        $days = $interval->days + 1;
        
        // Exclude weekends
        $workingDays = 0;
        $current = clone $start;
        while ($current <= $end) {
            $day = $current->format('N'); // 1=Mon ... 7=Sun
            if ($day < 6) { // Mon-Fri
                $workingDays++;
            }
            $current->modify('+1 day');
        }
        return $workingDays;
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
        
        // Fetch company holidays for the selected year - only mandatory holidays
        $companyHoliday = CompanyHoliday::where('year', $year)->first();
        $holidayDates = [];
        if ($companyHoliday) {
            // Only include mandatory holidays, not optional ones
            $mandatoryHolidays = $companyHoliday->mandatory_holidays ?? [];
            foreach ($mandatoryHolidays as $holiday) {
                $date = \Carbon\Carbon::parse($holiday['date'])->format('Y-m-d');
                $holidayDates[$date] = $holiday['name'];
            }
        }
        
        // Fetch already applied leave dates (pending or approved) for this user in the selected year
        $appliedLeaves = ApplyLeave::where('user_id', $user->id)
            ->where('year', $year)
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Build array of already applied dates with half-day info
        $appliedDates = [];
        $halfDayTaken = []; // Track which half is taken for each date: ['first_half' => [], 'second_half' => []]
        foreach ($appliedLeaves as $leave) {
            $start = \Carbon\Carbon::parse($leave->start_date);
            $end = \Carbon\Carbon::parse($leave->end_date);
            $current = $start;
            while ($current <= $end) {
                $dateStr = $current->format('Y-m-d');
                $leaveType = str_replace('_', ' ', ucwords($leave->leave_type, '_'));
                $appliedDates[$dateStr] = $leaveType . ' (' . $leave->status . ')';
                
                // Track half-day periods
                if ($leave->duration_type === 'half_day' && $leave->half_period) {
                    if ($leave->half_period === 'first_half') {
                        $halfDayTaken['first_half'][] = $dateStr;
                    } elseif ($leave->half_period === 'second_half') {
                        $halfDayTaken['second_half'][] = $dateStr;
                    }
                }
                $current->addDay();
            }
        }
        
        return view('User.leaves.apply', compact('leaveBalance', 'year', 'holidayDates', 'appliedDates', 'halfDayTaken'));
    }

    /**
     * Store a newly created leave application.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $year = date('Y');
        
        $request->validate([
            'leave_type' => 'required|in:sick_leave,casual_leave,earned_leave',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'duration_type' => 'required|in:full_day,half_day',
            'half_period' => 'nullable|in:first_half,second_half',
        ]);

        // Get company holidays for validation - only mandatory holidays
        $companyHoliday = CompanyHoliday::where('year', $year)->first();
        $holidayDates = [];
        if ($companyHoliday) {
            // Only include mandatory holidays, not optional ones
            $mandatoryHolidays = $companyHoliday->mandatory_holidays ?? [];
            foreach ($mandatoryHolidays as $holiday) {
                $date = \Carbon\Carbon::parse($holiday['date'])->format('Y-m-d');
                $holidayDates[$date] = $holiday['name'];
            }
        }

        // Check if selected dates include any company holidays
        $startDate = \Carbon\Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate = \Carbon\Carbon::parse($request->end_date)->format('Y-m-d');
        $conflictHolidays = [];
        
        $current = new \Carbon\Carbon($startDate);
        $end = new \Carbon\Carbon($endDate);
        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            if (isset($holidayDates[$dateStr])) {
                $conflictHolidays[] = $dateStr . ' (' . $holidayDates[$dateStr] . ')';
            }
            $current->addDay();
        }
        
        if (!empty($conflictHolidays)) {
            return back()->with('error', 'Cannot apply leave on company holiday(s): ' . implode(', ', $conflictHolidays))->withInput();
        }

        // Check if selected dates include any already applied (pending/approved) leaves
        $existingLeaves = ApplyLeave::where('user_id', $user->id)
            ->where('year', $year)
            ->whereIn('status', ['pending', 'approved'])
            ->get();
        
        $conflictAppliedLeaves = [];
        $requestStart = \Carbon\Carbon::parse($request->start_date);
        $requestEnd = \Carbon\Carbon::parse($request->end_date);
        $requestDurationType = $request->duration_type ?? 'full_day';
        $requestHalfPeriod = $request->half_period ?? null;
        
        foreach ($existingLeaves as $leave) {
            $leaveStart = \Carbon\Carbon::parse($leave->start_date);
            $leaveEnd = \Carbon\Carbon::parse($leave->end_date);
            
            // Check if there's any overlap
            if ($requestStart->lte($leaveEnd) && $requestEnd->gte($leaveStart)) {
                // Check for half-day compatibility
                $isHalfDayOverlap = false;
                $canApplyOtherHalf = false;
                
                if ($requestDurationType === 'half_day' && $leave->duration_type === 'half_day') {
                    // Both are half-day - check if they're for different halves
                    if ($requestHalfPeriod && $leave->half_period) {
                        if ($requestHalfPeriod !== $leave->half_period) {
                            // Different half - this is allowed
                            $canApplyOtherHalf = true;
                        } else {
                            // Same half - conflict
                            $isHalfDayOverlap = true;
                        }
                    }
                } elseif ($requestDurationType === 'half_day' && $leave->duration_type === 'full_day') {
                    // Can't apply half-day when full day is already taken
                    $isHalfDayOverlap = true;
                } elseif ($requestDurationType === 'full_day' && $leave->duration_type === 'half_day') {
                    // Can't apply full day when half-day is already taken
                    $isHalfDayOverlap = true;
                }
                
                if ($canApplyOtherHalf) {
                    continue; // Allow this - different half of the day
                }
                
                if ($isHalfDayOverlap) {
                    $leaveType = str_replace('_', ' ', ucwords($leave->leave_type, '_'));
                    $conflictAppliedLeaves[] = $leaveStart->format('Y-m-d') . ' to ' . $leaveEnd->format('Y-m-d') . ' (' . $leaveType . ' - ' . $leave->status . ')';
                }
            }
        }
        
        if (!empty($conflictAppliedLeaves)) {
            return back()->with('error', 'You already have leave applied for the selected dates: ' . implode(', ', $conflictAppliedLeaves))->withInput();
        }

        $leaveDays = $this->calculateLeaveDays($request->start_date, $request->end_date, $request->duration_type ?? 'full_day');
        
        // Half day logic handled in calculateLeaveDays()


        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        
        $availableLeaves = $leaveBalance[$request->leave_type] ?? 0;
        
        if ($leaveDays > $availableLeaves) {
            return back()->with('error', 'Insufficient leave balance. You have ' . $availableLeaves . ' ' . $request->leave_type . ' leaves available.');
        }

        ApplyLeave::create([
            'user_id' => $user->id,
            'leave_type' => $request->leave_type,
            'year' => $year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $leaveDays,
            'reason' => $request->reason,
            'status' => 'pending',
            'duration_type' => $request->duration_type ?? 'full_day',
            'half_period' => $request->half_period ?? null,
        ]);

        return redirect()->route(auth()->user()->role_id == 2 ? 'manager.leaves.index' : 'employee.leaves.index')->with('success', 'Leave application submitted successfully.');
    }

    /**
     * Cancel a leave application (only pending or approved leaves can be cancelled).
     */
    public function cancel($id)
    {
        $user = auth()->user();
        $leave = ApplyLeave::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Only pending or approved leaves can be cancelled
        if (!in_array($leave->status, ['pending', 'approved'])) {
            return back()->with('error', 'Only pending or approved leave applications can be cancelled.');
        }
        
        $leave->delete();
        
        return redirect()->route(auth()->user()->role_id == 2 ? 'manager.leaves.index' : 'employee.leaves.index')->with('success', 'Leave application cancelled successfully.');
    }

    /**
     * Show the edit form for a leave application.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $leave = ApplyLeave::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Only pending or approved leaves can be edited
        if (!in_array($leave->status, ['pending', 'approved'])) {
            return back()->with('error', 'Only pending or approved leave applications can be edited.');
        }
        
        $year = $leave->year;
        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        
        // Fetch company holidays for the selected year - only mandatory holidays
        $companyHoliday = CompanyHoliday::where('year', $year)->first();
        $holidayDates = [];
        if ($companyHoliday) {
            $mandatoryHolidays = $companyHoliday->mandatory_holidays ?? [];
            foreach ($mandatoryHolidays as $holiday) {
                $date = \Carbon\Carbon::parse($holiday['date'])->format('Y-m-d');
                $holidayDates[$date] = $holiday['name'];
            }
        }
        
        // Fetch already applied leave dates (excluding current leave)
        $appliedLeaves = ApplyLeave::where('user_id', $user->id)
            ->where('year', $year)
            ->whereIn('status', ['pending', 'approved'])
            ->where('id', '!=', $id)
            ->get();
        
        $appliedDates = [];
        $halfDayTaken = []; // Track which half is taken for each date
        foreach ($appliedLeaves as $appliedLeave) {
            $start = \Carbon\Carbon::parse($appliedLeave->start_date);
            $end = \Carbon\Carbon::parse($appliedLeave->end_date);
            $current = $start;
            while ($current <= $end) {
                $dateStr = $current->format('Y-m-d');
                $leaveType = str_replace('_', ' ', ucwords($appliedLeave->leave_type, '_'));
                $appliedDates[$dateStr] = $leaveType . ' (' . $appliedLeave->status . ')';
                
                // Track half-day periods
                if ($appliedLeave->duration_type === 'half_day' && $appliedLeave->half_period) {
                    if ($appliedLeave->half_period === 'first_half') {
                        $halfDayTaken['first_half'][] = $dateStr;
                    } elseif ($appliedLeave->half_period === 'second_half') {
                        $halfDayTaken['second_half'][] = $dateStr;
                    }
                }
                $current->addDay();
            }
        }
        
        return view('User.leaves.edit', compact('leave', 'leaveBalance', 'year', 'holidayDates', 'appliedDates', 'halfDayTaken'));
    }

    /**
     * Update a leave application.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $leave = ApplyLeave::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Only pending or approved leaves can be edited
        if (!in_array($leave->status, ['pending', 'approved'])) {
            return back()->with('error', 'Only pending or approved leave applications can be edited.');
        }
        
        $year = date('Y');
        
        $request->validate([
            'leave_type' => 'required|in:sick_leave,casual_leave,earned_leave',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'duration_type' => 'required|in:full_day,half_day',
            'half_period' => 'nullable|in:first_half,second_half',
        ]);

        // Get company holidays for validation - only mandatory holidays
        $companyHoliday = CompanyHoliday::where('year', $year)->first();
        $holidayDates = [];
        if ($companyHoliday) {
            $mandatoryHolidays = $companyHoliday->mandatory_holidays ?? [];
            foreach ($mandatoryHolidays as $holiday) {
                $date = \Carbon\Carbon::parse($holiday['date'])->format('Y-m-d');
                $holidayDates[$date] = $holiday['name'];
            }
        }

        // Check if selected dates include any company holidays
        $startDate = \Carbon\Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate = \Carbon\Carbon::parse($request->end_date)->format('Y-m-d');
        $conflictHolidays = [];
        
        $current = new \Carbon\Carbon($startDate);
        $end = new \Carbon\Carbon($endDate);
        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            if (isset($holidayDates[$dateStr])) {
                $conflictHolidays[] = $dateStr . ' (' . $holidayDates[$dateStr] . ')';
            }
            $current->addDay();
        }
        
        if (!empty($conflictHolidays)) {
            return back()->with('error', 'Cannot apply leave on company holiday(s): ' . implode(', ', $conflictHolidays))->withInput();
        }

        // Check if selected dates include any already applied (pending/approved) leaves (excluding current)
        $existingLeaves = ApplyLeave::where('user_id', $user->id)
            ->where('year', $year)
            ->whereIn('status', ['pending', 'approved'])
            ->where('id', '!=', $id)
            ->get();
        
        $conflictAppliedLeaves = [];
        $requestStart = \Carbon\Carbon::parse($request->start_date);
        $requestEnd = \Carbon\Carbon::parse($request->end_date);
        $requestDurationType = $request->duration_type ?? 'full_day';
        $requestHalfPeriod = $request->half_period ?? null;
        
        foreach ($existingLeaves as $existingLeave) {
            $leaveStart = \Carbon\Carbon::parse($existingLeave->start_date);
            $leaveEnd = \Carbon\Carbon::parse($existingLeave->end_date);
            
            // Check if there's any overlap
            if ($requestStart->lte($leaveEnd) && $requestEnd->gte($leaveStart)) {
                // Check for half-day compatibility
                $isHalfDayOverlap = false;
                $canApplyOtherHalf = false;
                
                if ($requestDurationType === 'half_day' && $existingLeave->duration_type === 'half_day') {
                    // Both are half-day - check if they're for different halves
                    if ($requestHalfPeriod && $existingLeave->half_period) {
                        if ($requestHalfPeriod !== $existingLeave->half_period) {
                            // Different half - this is allowed
                            $canApplyOtherHalf = true;
                        } else {
                            // Same half - conflict
                            $isHalfDayOverlap = true;
                        }
                    }
                } elseif ($requestDurationType === 'half_day' && $existingLeave->duration_type === 'full_day') {
                    // Can't apply half-day when full day is already taken
                    $isHalfDayOverlap = true;
                } elseif ($requestDurationType === 'full_day' && $existingLeave->duration_type === 'half_day') {
                    // Can't apply full day when half-day is already taken
                    $isHalfDayOverlap = true;
                }
                
                if ($canApplyOtherHalf) {
                    continue; // Allow this - different half of the day
                }
                
                if ($isHalfDayOverlap) {
                    $leaveType = str_replace('_', ' ', ucwords($existingLeave->leave_type, '_'));
                    $conflictAppliedLeaves[] = $leaveStart->format('Y-m-d') . ' to ' . $leaveEnd->format('Y-m-d') . ' (' . $leaveType . ' - ' . $existingLeave->status . ')';
                }
            }
        }
        
        if (!empty($conflictAppliedLeaves)) {
            return back()->with('error', 'You already have leave applied for the selected dates: ' . implode(', ', $conflictAppliedLeaves))->withInput();
        }

        $leaveDays = $this->calculateLeaveDays($request->start_date, $request->end_date, $request->duration_type ?? 'full_day');
        
        // Calculate the difference in days to adjust leave balance
        $originalDays = $leave->total_days;
        $dayDifference = $leaveDays - $originalDays;
        
        $leaveBalance = ApplyLeave::getLeaveBalance($user->id, $year);
        $availableLeaves = $leaveBalance[$request->leave_type] ?? 0;
        
        // For the same leave type, check if balance is sufficient for the new amount
        if ($leave->leave_type === $request->leave_type) {
            if ($leaveDays > ($availableLeaves + $originalDays)) {
                return back()->with('error', 'Insufficient leave balance. You have ' . ($availableLeaves + $originalDays) . ' ' . $request->leave_type . ' leaves available.');
            }
        } else {
            // Different leave type - need to add back original and check new
            if ($leaveDays > $availableLeaves) {
                return back()->with('error', 'Insufficient leave balance. You have ' . $availableLeaves . ' ' . $request->leave_type . ' leaves available.');
            }
        }

        $leave->update([
            'leave_type' => $request->leave_type,
            'year' => $year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $leaveDays,
            'reason' => $request->reason,
            'status' => 'pending', // Reset to pending when edited
            'duration_type' => $request->duration_type ?? 'full_day',
            'half_period' => $request->half_period ?? null,
        ]);

        return redirect()->route(auth()->user()->role_id == 2 ? 'manager.leaves.index' : 'employee.leaves.index')->with('success', 'Leave application updated successfully.');
    }
}
