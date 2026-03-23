# TODO: Disable Company Holidays in Leave Apply Form

## Steps:
- [x] 1. Update app/Http/Controllers/User/LeaveController.php: Fetch CompanyHoliday for $year, flatten dates to array['Y-m-d' => name], pass as $holidayDates to view.
- [x] 2. Update resources/views/User/leaves/apply.blade.php: Pass $holidayDates to JS, extend beforeShowDay to disable/mark holidays, add CSS.
- [x] 3. Test: Add holiday via admin, verify disabled in leave apply datepicker with tooltip.

Status: ✅ Complete
