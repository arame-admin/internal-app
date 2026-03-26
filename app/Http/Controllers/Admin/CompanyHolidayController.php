<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use App\Models\MandatoryHoliday;
use App\Models\OptionalHoliday;
use Illuminate\Http\Request;

class CompanyHolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $companyHoliday = CompanyHoliday::with(['mandatoryHolidays', 'optionalHolidays'])
            ->where('year', $year)
            ->first();
        return view('Admin.company-holidays.index', compact('companyHoliday', 'year'));
    }

    public function create(Request $request)
    {
        $selectedYear = $request->get('year', date('Y') + 1);
        return view('Admin.company-holidays.create', compact('selectedYear'));
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

        // Create company holiday
        $companyHoliday = CompanyHoliday::create([
            'year' => $request->year,
        ]);

        // Save mandatory holidays
        foreach ($request->mandatory_holidays as $holiday) {
            MandatoryHoliday::create([
                'company_holiday_id' => $companyHoliday->id,
                'date' => $holiday['date'],
                'name' => $holiday['name'],
                'day' => isset($holiday['day']) ? $holiday['day'] : null,
            ]);
        }

        // Filter and save optional holidays
        if ($request->optional_holidays) {
            foreach ($request->optional_holidays as $holiday) {
                if (!empty($holiday['date']) && !empty($holiday['name'])) {
                    OptionalHoliday::create([
                        'company_holiday_id' => $companyHoliday->id,
                        'date' => $holiday['date'],
                        'name' => $holiday['name'],
                        'day' => isset($holiday['day']) ? $holiday['day'] : null,
                    ]);
                }
            }
        }

        return redirect()->route('company-holidays.index', ['year' => $request->year])
            ->with('success', 'Company holidays created successfully.');
    }

    public function edit($id)
    {
        $companyHoliday = CompanyHoliday::with(['mandatoryHolidays', 'optionalHolidays'])->findOrFail($id);
        return view('Admin.company-holidays.edit', compact('companyHoliday'));
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

        // Update company holiday
        $companyHoliday->update([
            'year' => $request->year,
        ]);

        // Delete old holidays and create new ones
        MandatoryHoliday::where('company_holiday_id', $companyHoliday->id)->delete();
        OptionalHoliday::where('company_holiday_id', $companyHoliday->id)->delete();

        // Save mandatory holidays
        foreach ($request->mandatory_holidays as $holiday) {
            MandatoryHoliday::create([
                'company_holiday_id' => $companyHoliday->id,
                'date' => $holiday['date'],
                'name' => $holiday['name'],
                'day' => isset($holiday['day']) ? $holiday['day'] : null,
            ]);
        }

        // Filter and save optional holidays
        if ($request->optional_holidays) {
            foreach ($request->optional_holidays as $holiday) {
                if (!empty($holiday['date']) && !empty($holiday['name'])) {
                    OptionalHoliday::create([
                        'company_holiday_id' => $companyHoliday->id,
                        'date' => $holiday['date'],
                        'name' => $holiday['name'],
                        'day' => isset($holiday['day']) ? $holiday['day'] : null,
                    ]);
                }
            }
        }

        return redirect()->route('company-holidays.index', ['year' => $request->year])
            ->with('success', 'Company holidays updated successfully.');
    }

    public function destroy($id)
    {
        $companyHoliday = CompanyHoliday::findOrFail($id);
        
        // Delete related holidays first
        MandatoryHoliday::where('company_holiday_id', $id)->delete();
        OptionalHoliday::where('company_holiday_id', $id)->delete();
        
        $companyHoliday->delete();
        return redirect()->route('company-holidays.index')
            ->with('success', 'Company holidays deleted successfully.');
    }
}
