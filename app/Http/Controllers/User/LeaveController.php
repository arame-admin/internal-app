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
        
        // Fetch company holidays for the selected year
        $companyHoliday = CompanyHoliday::where('year', $year)->first();
        $holidayDates = [];
        if ($companyHoliday) {
            $allHolidays = array_merge($companyHoliday->mandatory_holidays ?? [], $companyHoliday->optional_holidays ?? []);
            foreach ($allHolidays as $holiday) {
                $date = \Carbon\Carbon::parse($holiday['date'])->format('Y-m-d');
                $holidayDates[$date] = $holiday['name'];
            }
        }
        
        $appliedLeaves = ApplyLeave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('User.leaves.apply', compact('leaveBalance', 'year', 'holidayDates', 'appliedLeaves'));
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

        return redirect()->route('employee.leaves.index')->with('success', 'Leave application submitted successfully.');
    }
}
