# JSON to Normalized Tables Migration - Progress Tracker

## Current Status: ✅ Setup Complete - Models Updated

### Step 1: ✅ Create TODO.md
### Step 2: ✅ Create migrations (3 files ready)
### Step 3: ✅ Create data migration command (`php artisan migrate:json-data`)

### Step 4: ✅ Update Models
- ✅ Project.php (removed JSON casts/fillable)
- [ ] Timesheet.php (add projectTask relation)

### Step 5: [ ] Fix Views/Controllers
- [ ] Admin/projects/edit.blade.php & create.blade.php (use relations)
- [ ] Timesheet controllers/views (task → project_task_id)

### Step 6: [ ] Execute
- [ ] `php artisan migrate:json-data --dry-run`
- [ ] `php artisan migrate`
- [ ] Test CRUD operations

### Step 7: [ ] Cleanup & Complete 🎉

**Next:** Update Timesheet model
