<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use Illuminate\Http\Request;

class CompanyHolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $companyHolidays = CompanyHoliday::all();
        return view('company-holidays.index', compact('companyHolidays', 'year'));
    }

    public function create(Request $request)
    {
        $selectedYear = $request->get('year', date('Y') + 1);
        return view('company-holidays.create', compact('selectedYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|unique:company_holidays,year',
            'mandatory_holidays' => 'required|array|min:1',
            'mandatory_holidays.*.date' => 'required|date',
            'mandatory_holidays.*.name' => 'required|string|max:255',
            'optional_holidays' => 'nullable|array',
            'optional_holidays.*.date' => 'nullable|date',
            'optional_holidays.*.name' => 'nullable|string|max:255',
        ]);

        // Filter out empty optional holidays
        $optionalHolidays = $request->optional_holidays;
        if ($optionalHolidays) {
            $optionalHolidays = array_filter($optionalHolidays, function ($holiday) {
                return !empty($holiday['date']) && !empty($holiday['name']);
            });
            $optionalHolidays = array_values($optionalHolidays);
        } else {
            $optionalHolidays = [];
        }

        CompanyHoliday::create([
            'year' => $request->year,
            'mandatory_holidays' => $request->mandatory_holidays,
            'optional_holidays' => $optionalHolidays,
        ]);
        return redirect()->route('company-holidays.index', ['year' => $request->year])
            ->with('success', 'Company holidays created successfully.');
    }

    public function edit($id)
    {
        $companyHoliday = CompanyHoliday::findOrFail($id);
        return view('company-holidays.edit', compact('companyHoliday'));
    }

    public function update(Request $request, $id)
    {
        $companyHoliday = CompanyHoliday::findOrFail($id);
        $request->validate([
            'year' => 'required|integer|unique:company_holidays,year,' . $id,
            'mandatory_holidays' => 'required|array|min:1',
            'mandatory_holidays.*.date' => 'required|date',
            'mandatory_holidays.*.name' => 'required|string|max:255',
            'optional_holidays' => 'nullable|array',
            'optional_holidays.*.date' => 'nullable|date',
            'optional_holidays.*.name' => 'nullable|string|max:255',
        ]);

        // Filter out empty optional holidays
        $optionalHolidays = $request->optional_holidays;
        if ($optionalHolidays) {
            $optionalHolidays = array_filter($optionalHolidays, function ($holiday) {
                return !empty($holiday['date']) && !empty($holiday['name']);
            });
            $optionalHolidays = array_values($optionalHolidays);
        } else {
            $optionalHolidays = [];
        }

        $companyHoliday->update([
            'year' => $request->year,
            'mandatory_holidays' => $request->mandatory_holidays,
            'optional_holidays' => $optionalHolidays,
        ]);
        return redirect()->route('company-holidays.index', ['year' => $request->year])
            ->with('success', 'Company holidays updated successfully.');
    }

    public function destroy($id)
    {
        $companyHoliday = CompanyHoliday::findOrFail($id);
        $companyHoliday->delete();
        return redirect()->route('company-holidays.index')
            ->with('success', 'Company holidays deleted successfully.');
    }
}
