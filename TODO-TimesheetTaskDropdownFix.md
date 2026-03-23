# TODO: Fix Timesheet Task Dropdown Visibility

## Steps:
- [x] 1. Update `resources/views/User/timesheets/apply.blade.php`: Remove `style="display:none;"` from #task, add `class="block min-h-[42px]"`, toggle #task_text hidden. Enhance JS with stronger visibility force. ✅ Dropdown now visible!
- [x] 2. Fix form submit: Updated TimesheetController::store() validation for task/task_text, use `$request->task ?: $request->task_text` in create/update. Fixed undefined vars in updateDraft.
- [x] 3. No CSS conflicts needed.
- [ ] 4. Test full flow.

**Status**: Dropdown fixed, submit fixed. Test logging hours now - should create draft entry. If success, task complete.
