<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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
        $existingHoliday = CompanyHoliday::where('year', $selectedYear)->first();
        $existingHolidayDates = $existingHoliday?->getAllHolidayDates() ?? [];
return view('company-holidays.create-fixed', compact('selectedYear', 'existingHolidayDates'));
    }

    public function store(Request $request)
    {
        Log::info('CompanyHoliday store request', ['request' => $request->all()]);

$request->validate([
'year' => 'required|integer|min:2000|max:2100|unique:company_holidays,year',
            'mandatory_holidays' => 'required|array',
            'mandatory_holidays.*.date' => 'nullable|date_format:Y-m-d',
            'mandatory_holidays.*.name' => 'nullable|string|max:255|min:2',
            'optional_holidays' => 'nullable|array',
            'optional_holidays.*.date' => 'nullable|date_format:Y-m-d',
            'optional_holidays.*.name' => 'nullable|string|max:255|min:2',
            'status' => 'nullable|in:active,inactive',
        ]);



        Log::info('Raw mandatory count before filter', ['count' => count($request->mandatory_holidays ?? [])]);
        Log::info('Raw optional count before filter', ['count' => count($request->optional_holidays ?? [])]);

        // Filter out empty mandatory holidays
        $mandatoryHolidays = array_filter($request->mandatory_holidays, function ($holiday) {
            return !empty($holiday['date']) && !empty($holiday['name']);
        });
        if (empty($mandatoryHolidays)) {
            return back()->withErrors(['mandatory_holidays' => 'At least one mandatory holiday with date and name is required.']);
        }
        $mandatoryHolidays = array_values($mandatoryHolidays);

        // Filter out empty optional holidays
        $optionalHolidays = $request->optional_holidays ?? [];
        $optionalHolidays = array_filter($optionalHolidays, function ($holiday) {
            return !empty($holiday['date']) && !empty($holiday['name']);
        });
        $optionalHolidays = array_values($optionalHolidays);

        Log::info('Filtered mandatory', ['count' => count($mandatoryHolidays), 'data' => $mandatoryHolidays]);
        Log::info('Filtered optional', ['count' => count($optionalHolidays), 'data' => $optionalHolidays]);

        $createData = [
            'year' => $request->year,
            'mandatory_holidays' => $mandatoryHolidays,
            'optional_holidays' => $optionalHolidays,
            'status' => $request->filled('status') ? $request->status : 'active',
        ];

        Log::info('Final data to save', $createData);

        try {
            $newHoliday = CompanyHoliday::create($createData);
            
            // Verify persisted in DB
            Log::info('CompanyHoliday created successfully', ['id' => $newHoliday->id, 'year' => $request->year]);
        } catch (\Exception $e) {
            Log::error('CompanyHoliday create exception', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'data' => $createData
            ]);
            return back()->with('error', 'Failed to save holidays: ' . $e->getMessage() . '. Check date formats (YYYY-MM-DD) and ensure year is unique.')->withInput();
        }


        return redirect()->route('company-holidays.index', ['year' => $request->year])
            ->with('success', 'Company holidays created successfully.');
    }

    public function edit($id)
    {
        $companyHoliday = CompanyHoliday::findOrFail($id);
        Log::info('CompanyHoliday edit loaded', ['id' => $id, 'data' => $companyHoliday->toArray()]);
        $existingHolidayDates = $companyHoliday->getAllHolidayDates();
        return view('company-holidays.edit-fixed', compact('companyHoliday', 'existingHolidayDates'));
    }

    public function update(Request $request, $id)
    {
        $companyHoliday = CompanyHoliday::findOrFail($id);
        Log::info('CompanyHoliday update request', ['id' => $id, 'request' => $request->all()]);

        $request->validate([
'year' => 'required|integer|min:2000|max:2100|unique:company_holidays,year,' . $id,
            'mandatory_holidays' => 'required|array',
            'mandatory_holidays.*.date' => 'nullable|date_format:Y-m-d',
            'mandatory_holidays.*.name' => 'nullable|string|max:255|min:2',
            'optional_holidays' => 'nullable|array',
            'optional_holidays.*.date' => 'nullable|date_format:Y-m-d',
            'optional_holidays.*.name' => 'nullable|string|max:255|min:2',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Pre-validate filtered arrays
        $rawMandatory = $request->mandatory_holidays;
        $validMandatory = array_filter($rawMandatory, fn($h) => !empty(trim($h['date'] ?? '')) && !empty(trim($h['name'] ?? '')));
        if (empty($validMandatory)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'mandatory_holidays' => ['At least one valid mandatory holiday is required.']
            ]);
        }

        Log::info('Raw mandatory count before filter', ['count' => count($request->mandatory_holidays ?? [])]);
        Log::info('Raw optional count before filter', ['count' => count($request->optional_holidays ?? [])]);

        // Filter out empty mandatory holidays
        $mandatoryHolidays = array_filter($request->mandatory_holidays, function ($holiday) {
            return !empty($holiday['date']) && !empty($holiday['name']);
        });
        if (empty($mandatoryHolidays)) {
            Log::warning('No valid mandatory holidays after filter');
            return back()->withErrors(['mandatory_holidays' => 'At least one mandatory holiday with date and name is required.']);
        }
        $mandatoryHolidays = array_values($mandatoryHolidays);

        // Filter out empty optional holidays
        $optionalHolidays = $request->optional_holidays ?? [];
        $optionalHolidays = array_filter($optionalHolidays, function ($holiday) {
            return !empty($holiday['date']) && !empty($holiday['name']);
        });
        $optionalHolidays = array_values($optionalHolidays);

        Log::info('Filtered mandatory', ['count' => count($mandatoryHolidays), 'data' => $mandatoryHolidays]);
        Log::info('Filtered optional', ['count' => count($optionalHolidays), 'data' => $optionalHolidays]);

        $updateData = [
            'year' => $request->year,
            'mandatory_holidays' => $mandatoryHolidays,
            'optional_holidays' => $optionalHolidays,
        ];

        // Handle status update if provided
        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        Log::info('Final data to save', ['id' => $id, 'data' => $updateData]);

        try {
            $result = $companyHoliday->update($updateData);
            Log::info('Update result', ['id' => $id, 'affected_rows' => $result, 'update_data' => $updateData]);
            
            if (!$result) {
                Log::error('CompanyHoliday update failed: no rows affected');
                return back()->with('error', 'No changes detected or update failed. Verify holiday dates and names.')->withInput();
            }
            
            $companyHoliday->refresh();
            $freshHoliday = $companyHoliday->fresh();
            if (!$freshHoliday || !$freshHoliday->exists) {
                Log::error('CompanyHoliday fresh check failed after update');
                return back()->with('error', 'Update verification failed.')->withInput();
            }
        } catch (\Exception $e) {
            Log::error('CompanyHoliday update exception', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'data' => $updateData]);
            return back()->with('error', 'Update failed: ' . $e->getMessage())->withInput();
        }

        $companyHoliday->refresh();
        $dbFresh = $companyHoliday->fresh();
        Log::info('CompanyHoliday after refresh', ['id' => $id, 'in_memory' => $companyHoliday->toArray()]);
        Log::info('CompanyHoliday DB fresh', ['id' => $id, 'fresh' => $dbFresh ? $dbFresh->toArray() : 'null']);


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
