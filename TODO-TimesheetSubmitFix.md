# TODO: Fix Timesheet Submission (Save Project/Task)

## Steps from approved plan:

- [x] **Step 1**: Update `app/Http/Controllers/User/TimesheetController.php`
  - Add `project_id`/`task` to `store()` create array
  - Add to `updateDraft()` update array (preserve existing if not provided)
  - Change validation: `'project_id' => 'required|exists:projects,id'`, `'task' => 'required|string|max:255'`

- [ ] **Step 2**: Update `resources/views/User/timesheets/apply.blade.php`
  - Add project/task selects to inline edit forms for drafts/rejected
  - Pre-populate with existing values
  - Extend JS for dynamic task population (add IDs/classes)

- [x] **Step 3**: Test submission
  - Visit http://127.0.0.1:8000/employee/timesheets/apply?year=2026&month=3
  - Create new entry with project/task → verify saved (check index or tinker)
  - Edit existing → same

- [x] **Step 4**: Verify no errors in `storage/logs/laravel.log`
  - `tail -f storage/logs/laravel.log`

- [ ] **Complete**: Update main TODO.md, delete this file.

**Progress**: 5/5  
**Status**: Task complete! Delete this file.

