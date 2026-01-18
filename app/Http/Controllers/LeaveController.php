<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display the leaves management page.
     */
    public function leaves()
    {
        return view('leaves.index');
    }

    /**
     * Display the company holidays management page.
     */
    public function index(Request $request)
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
    public function create()
    {
        return view('company-holidays.create');
    }

    /**
     * Store a newly created company holiday.
     */
    public function store(Request $request)
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
    public function show($id)
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
    public function edit($id)
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
    public function update(Request $request, $id)
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
    public function destroy($id)
    {
        // In a real application, this would delete from the database
        // For now, we'll just redirect back with success message
        return redirect()->route('company-holidays.index')->with('success', 'Company holiday deleted successfully.');
    }

    /**
     * Show status change form for the specified company holiday.
     */
    public function showStatus($id)
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