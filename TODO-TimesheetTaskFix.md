# Fix Timesheet Task Selection Disabled Issue

## Steps:

### 1. Populate seed data [ ]
- Run: php artisan db:seed --class=MasterDataSeeder

### 2. Verify projects data [ ]
- Check: php artisan tinker \"App\\\\Models\\\\Project::with(&#39;department&#39;)->where(&#39;status&#39;, &#39;!=&#39;, &#39;cancelled&#39;)->get()\"

### 3. Update TimesheetController.php [ ]
- Ensure fallback tasks always array
- Log empty projects if any

### 4. Update apply.blade.php JS [ ]
- Always enable task select after project change
- If no tasks, switch to text input

### 5. Test form [ ]
- Visit /employee/timesheets/apply
- Select project → task enables/prompts input
- Submit timesheet

### 6. Cleanup [ ]
- Update TODO-TimesheetTaskSelect.md as resolved
- Delete this file

**Progress: 4/6** (Seeder run, view HTML/JS updated for toggle input/select)
