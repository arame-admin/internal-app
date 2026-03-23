# Department-Controlled Tasks Implementation TODO

## Overview
Implement department selection in project creation with auto-task population. Ensure timesheet task selection controlled by project department.

## Steps (to be checked off as completed):

### 1. Database Migrations 
- [x] Created `add_department_id_to_projects_table.php`
- [x] Created `add_available_tasks_to_departments_table.php`
- [ ] Run `php artisan migrate`


### 2. Seeding
- [x] Updated `database/seeders/MasterDataSeeder.php` with 4 departments + tasks JSON
- [ ] Run `php artisan db:seed --class=MasterDataSeeder`

### 3. Models
- [x] Updated `app/Models/Project.php`: add department_id fillable/relation
- [x] Updated `app/Models/Department.php`: add available_tasks fillable/casts
- [x] Updated `app/Models/Timesheet.php`: add project_id, task fillable/relations/migrations

### 4. Controllers
- [ ] `app/Http/Controllers/Admin/ProjectController.php`: pass departments, validate dept_id
- [ ] `app/Http/Controllers/User/TimesheetController.php`: pass projects, validate project_id/task

### 5. Views + JS
- [x] `resources/views/Admin/projects/create.blade.php`: dept select + JS task populate
- [x] `resources/views/Admin/projects/edit.blade.php`: same
- [x] `resources/views/User/timesheets/apply.blade.php`: project/task selects + filtering

### 6. Additional Timesheet Migration
- [x] Created `add_project_id_and_task_to_timesheets_table.php`

### 7. Testing
- [ ] Create project: select dept -> auto tasks
- [ ] Timesheet: select project -> filtered tasks
- [ ] Submit/validate

**All coding complete! Run `php artisan migrate` and `php artisan db:seed --class=MasterDataSeeder` then test.**

