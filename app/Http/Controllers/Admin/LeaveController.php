<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    /**
     * Display the leaves list page.
     */
    public function index(Request $request)
    {
        // Mock leave data
        $leaves = [
            [
                'id' => 1,
                'year' => 2024,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'active',
                'created_at' => '2023-01-01'
            ],
            [
                'id' => 2,
                'year' => 2025,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'active',
                'created_at' => '2024-01-01'
            ],
            [
                'id' => 3,
                'year' => 2026,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'inactive',
                'created_at' => '2025-01-01'
            ],
        ];

        // Filter by year if provided
        if ($request->filled('year')) {
            $leaves = array_filter($leaves, function($leave) use ($request) {
                return $leave['year'] == $request->year;
            });
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $leaves = array_filter($leaves, function($leave) use ($request) {
                return $leave['status'] == $request->status;
            });
        }

        // Sort by year if requested
        if ($request->filled('sort') && $request->sort == 'year') {
            usort($leaves, function($a, $b) {
                return $b['year'] <=> $a['year'];
            });
        }

        return view('leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new leave configuration.
     */
    public function create()
    {
        return view('leaves.create');
    }

    /**
     * Store a newly created leave configuration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),
            'sick_leaves' => 'required|integer|min:0',
            'casual_leaves' => 'required|integer|min:0',
            'earned_leaves' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        // In a real app, save to database
        // For now, redirect with success message
        return redirect()->route('leaves.index')->with('success', 'Leave configuration created successfully.');
    }

    /**
     * Show the form for editing the specified leave configuration.
     */
    public function edit($id)
    {
        // Mock finding the leave
        $leaves = [
            [
                'id' => 1,
                'year' => 2024,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'active',
                'created_at' => '2023-01-01'
            ],
            [
                'id' => 2,
                'year' => 2025,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'active',
                'created_at' => '2024-01-01'
            ],
            [
                'id' => 3,
                'year' => 2026,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'inactive',
                'created_at' => '2025-01-01'
            ],
        ];

        $leave = collect($leaves)->firstWhere('id', $id);

        if (!$leave) {
            abort(404);
        }

        return view('leaves.edit', compact('leave'));
    }

    /**
     * Update the specified leave configuration.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),
            'sick_leaves' => 'required|integer|min:0',
            'casual_leaves' => 'required|integer|min:0',
            'earned_leaves' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        // In a real app, update in database
        // For now, redirect with success message
        return redirect()->route('leaves.index')->with('success', 'Leave configuration updated successfully.');
    }

    /**
     * Remove the specified leave configuration.
     */
    public function destroy($id)
    {
        // In a real app, delete from database
        // For now, redirect with success message
        return redirect()->route('leaves.index')->with('success', 'Leave configuration deleted successfully.');
    }

    /**
     * Show status change page for leave configuration.
     */
    public function showStatus($id)
    {
        // Mock finding the leave
        $leaves = [
            [
                'id' => 1,
                'year' => 2024,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'active',
                'created_at' => '2023-01-01'
            ],
            [
                'id' => 2,
                'year' => 2025,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'active',
                'created_at' => '2024-01-01'
            ],
            [
                'id' => 3,
                'year' => 2026,
                'sick_leaves' => 10,
                'casual_leaves' => 12,
                'earned_leaves' => 15,
                'status' => 'inactive',
                'created_at' => '2025-01-01'
            ],
        ];

        $leave = collect($leaves)->firstWhere('id', $id);

        if (!$leave) {
            abort(404);
        }

        return view('leaves.status', compact('leave'));
    }

    /**
     * Display the leaves calendar page.
     */
    public function leaves()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        // Mock employee birthday data
        $employees = [
            ['name' => 'John Doe', 'birth_date' => '1990-01-15'],
            ['name' => 'Jane Smith', 'birth_date' => '1985-02-20'],
            ['name' => 'Bob Johnson', 'birth_date' => '1992-03-10'],
            ['name' => 'Alice Brown', 'birth_date' => '1988-04-05'],
            ['name' => 'Charlie Wilson', 'birth_date' => '1995-05-25'],
            ['name' => 'Diana Davis', 'birth_date' => '1980-06-12'],
            ['name' => 'Eve Miller', 'birth_date' => '1993-07-08'],
            ['name' => 'Frank Garcia', 'birth_date' => '1987-08-30'],
            ['name' => 'Grace Lee', 'birth_date' => '1991-09-14'],
            ['name' => 'Henry Taylor', 'birth_date' => '1984-10-22'],
            ['name' => 'Ivy Anderson', 'birth_date' => '1996-11-03'],
            ['name' => 'Jack Thomas', 'birth_date' => '1982-12-18'],
        ];

        return view('leaves.calendar', compact('currentYear', 'currentMonth', 'employees'));
    }

    /**
     * Display the company holidays management page.
     */
    public function holidayIndex(Request $request)
    {
        // Sample data - replace with actual database query
        $companyHolidays = [
            [
                'id' => 1,
                'year' => 2024,
                'mandatory_holidays' => [
                    ['date' => '2024-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2024-01-26', 'name' => 'Republic Day'],
                    ['date' => '2024-03-29', 'name' => 'Good Friday'],
                    ['date' => '2024-05-01', 'name' => 'Labour Day'],
                    ['date' => '2024-08-15', 'name' => 'Independence Day'],
                    ['date' => '2024-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2024-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [
                    ['date' => '2024-02-14', 'name' => 'Valentine\'s Day'],
                    ['date' => '2024-03-08', 'name' => 'Women\'s Day'],
                    ['date' => '2024-11-11', 'name' => 'Diwali'],
                ],
                'status' => 'active',
                'created_at' => '2023-12-01'
            ],
            [
                'id' => 2,
                'year' => 2025,
                'mandatory_holidays' => [
                    ['date' => '2025-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2025-01-26', 'name' => 'Republic Day'],
                    ['date' => '2025-04-18', 'name' => 'Good Friday'],
                    ['date' => '2025-05-01', 'name' => 'Labour Day'],
                    ['date' => '2025-08-15', 'name' => 'Independence Day'],
                    ['date' => '2025-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2025-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [
                    ['date' => '2025-02-14', 'name' => 'Valentine\'s Day'],
                    ['date' => '2025-03-08', 'name' => 'Women\'s Day'],
                ],
                'status' => 'active',
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 3,
                'year' => 2026,
                'mandatory_holidays' => [
                    ['date' => '2026-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2026-01-26', 'name' => 'Republic Day'],
                    ['date' => '2026-04-03', 'name' => 'Good Friday'],
                    ['date' => '2026-05-01', 'name' => 'Labour Day'],
                    ['date' => '2026-08-15', 'name' => 'Independence Day'],
                    ['date' => '2026-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2026-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [],
                'status' => 'inactive',
                'created_at' => '2025-12-01'
            ],
        ];

        return view('company-holidays.index', compact('companyHolidays'));
    }

    /**
     * Show the form for creating a new company holiday.
     */
    public function holidayCreate()
    {
        return view('company-holidays.create');
    }

    /**
     * Store a newly created company holiday.
     */
    public function holidayStore(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030|unique:company_holidays,year',
            'mandatory_holidays' => 'required|array|min:1',
            'mandatory_holidays.*.date' => 'required|date|date_format:Y-m-d',
            'mandatory_holidays.*.name' => 'required|string|max:255',
            'optional_holidays' => 'nullable|array',
            'optional_holidays.*.date' => 'required_with:optional_holidays|date|date_format:Y-m-d',
            'optional_holidays.*.name' => 'required_with:optional_holidays|string|max:255',
        ]);

        // In a real application, this would save to the database
        // For now, we'll just redirect back with success message
        return redirect()->route('company-holidays.index')->with('success', 'Company holiday created successfully.');
    }

    /**
     * Display the specified company holiday.
     */
    public function holidayShow($id)
    {
        // Sample data - replace with actual database query
        $holiday = [
            'id' => $id,
            'year' => 2024,
            'mandatory_holidays' => 12,
            'optional_holidays' => 5,
            'total_holidays' => 17,
            'status' => 'active',
            'created_at' => '2023-12-01',
        ];

        return view('company-holidays.show', compact('holiday'));
    }

    /**
     * Show the form for editing the specified company holiday.
     */
    public function holidayEdit($id)
    {
        // Sample data - replace with actual database query
        $holidays = [
            [
                'id' => 1,
                'year' => 2024,
                'mandatory_holidays' => [
                    ['date' => '2024-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2024-01-26', 'name' => 'Republic Day'],
                    ['date' => '2024-03-29', 'name' => 'Good Friday'],
                    ['date' => '2024-05-01', 'name' => 'Labour Day'],
                    ['date' => '2024-08-15', 'name' => 'Independence Day'],
                    ['date' => '2024-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2024-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [
                    ['date' => '2024-02-14', 'name' => 'Valentine\'s Day'],
                    ['date' => '2024-03-08', 'name' => 'Women\'s Day'],
                    ['date' => '2024-11-11', 'name' => 'Diwali'],
                ],
                'status' => 'active',
                'created_at' => '2023-12-01'
            ],
            [
                'id' => 2,
                'year' => 2025,
                'mandatory_holidays' => [
                    ['date' => '2025-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2025-01-26', 'name' => 'Republic Day'],
                    ['date' => '2025-04-18', 'name' => 'Good Friday'],
                    ['date' => '2025-05-01', 'name' => 'Labour Day'],
                    ['date' => '2025-08-15', 'name' => 'Independence Day'],
                    ['date' => '2025-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2025-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [
                    ['date' => '2025-02-14', 'name' => 'Valentine\'s Day'],
                    ['date' => '2025-03-08', 'name' => 'Women\'s Day'],
                    ['date' => '2025-11-11', 'name' => 'Diwali'],
                ],
                'status' => 'active',
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 3,
                'year' => 2026,
                'mandatory_holidays' => [
                    ['date' => '2026-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2026-01-26', 'name' => 'Republic Day'],
                    ['date' => '2026-04-03', 'name' => 'Good Friday'],
                    ['date' => '2026-05-01', 'name' => 'Labour Day'],
                    ['date' => '2026-08-15', 'name' => 'Independence Day'],
                    ['date' => '2026-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2026-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [
                    ['date' => '2026-02-14', 'name' => 'Valentine\'s Day'],
                    ['date' => '2026-03-08', 'name' => 'Women\'s Day'],
                    ['date' => '2026-10-31', 'name' => 'Halloween'],
                ],
                'status' => 'inactive',
                'created_at' => '2025-12-01'
            ],
        ];

        $holiday = collect($holidays)->firstWhere('id', (int)$id);

        if (!$holiday) {
            abort(404, 'Holiday year not found');
        }

        return view('company-holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified company holiday.
     */
    public function holidayUpdate(Request $request, $id)
    {
        // Check if this is a status update or full update
        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            // In a real application, this would update the database
            // For now, we'll just redirect back with success message
            return redirect()->route('company-holidays.index')->with('success', 'Holiday status updated successfully.');
        } else {
            $request->validate([
                'year' => 'required|integer|min:2020|max:2030|unique:company_holidays,year,' . $id,
                'mandatory_holidays' => 'required|array|min:1',
                'mandatory_holidays.*.date' => 'required|date|date_format:Y-m-d',
                'mandatory_holidays.*.name' => 'required|string|max:255',
                'optional_holidays' => 'nullable|array',
                'optional_holidays.*.date' => 'required_with:optional_holidays|date|date_format:Y-m-d',
                'optional_holidays.*.name' => 'required_with:optional_holidays|string|max:255',
            ]);

            // In a real application, this would update the database
            // For now, we'll just redirect back with success message
            return redirect()->route('company-holidays.index')->with('success', 'Company holiday updated successfully.');
        }
    }

    /**
     * Remove the specified company holiday.
     */
    public function holidayDestroy($id)
    {
        // In a real application, this would delete from the database
        // For now, we'll just redirect back with success message
        return redirect()->route('company-holidays.index')->with('success', 'Company holiday deleted successfully.');
    }

    /**
     * Show status change form for the specified company holiday.
     */
    public function holidayShowStatus($id)
    {
        // Sample data - replace with actual database query
        $holidays = [
            [
                'id' => 1,
                'year' => 2024,
                'mandatory_holidays' => [
                    ['date' => '2024-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2024-01-26', 'name' => 'Republic Day'],
                    ['date' => '2024-03-29', 'name' => 'Good Friday'],
                    ['date' => '2024-05-01', 'name' => 'Labour Day'],
                    ['date' => '2024-08-15', 'name' => 'Independence Day'],
                    ['date' => '2024-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2024-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [
                    ['date' => '2024-02-14', 'name' => 'Valentine\'s Day'],
                    ['date' => '2024-03-08', 'name' => 'Women\'s Day'],
                    ['date' => '2024-11-11', 'name' => 'Diwali'],
                ],
                'status' => 'active',
                'created_at' => '2023-12-01'
            ],
            [
                'id' => 2,
                'year' => 2025,
                'mandatory_holidays' => [
                    ['date' => '2025-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2025-01-26', 'name' => 'Republic Day'],
                    ['date' => '2025-04-18', 'name' => 'Good Friday'],
                    ['date' => '2025-05-01', 'name' => 'Labour Day'],
                    ['date' => '2025-08-15', 'name' => 'Independence Day'],
                    ['date' => '2025-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2025-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [
                    ['date' => '2025-02-14', 'name' => 'Valentine\'s Day'],
                    ['date' => '2025-03-08', 'name' => 'Women\'s Day'],
                ],
                'status' => 'active',
                'created_at' => '2024-12-01'
            ],
            [
                'id' => 3,
                'year' => 2026,
                'mandatory_holidays' => [
                    ['date' => '2026-01-01', 'name' => 'New Year\'s Day'],
                    ['date' => '2026-01-26', 'name' => 'Republic Day'],
                    ['date' => '2026-04-03', 'name' => 'Good Friday'],
                    ['date' => '2026-05-01', 'name' => 'Labour Day'],
                    ['date' => '2026-08-15', 'name' => 'Independence Day'],
                    ['date' => '2026-10-02', 'name' => 'Gandhi Jayanti'],
                    ['date' => '2026-12-25', 'name' => 'Christmas Day'],
                ],
                'optional_holidays' => [],
                'status' => 'inactive',
                'created_at' => '2025-12-01'
            ],
        ];

        $holiday = collect($holidays)->firstWhere('id', (int)$id);

        if (!$holiday) {
            abort(404, 'Holiday year not found');
        }

        return view('company-holidays.status', compact('holiday'));
    }

    /**
     * Legacy method for company holidays - redirects to index
     */
    public function companyHolidays(Request $request)
    {
        return $this->index($request);
    }
}