<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\BusinessUnitController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\LeaveApplicationsController;
use App\Http\Controllers\Admin\CompanyHolidayController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\User\LeaveController as UserLeaveController;
use App\Http\Controllers\User\TimesheetController as UserTimesheetController;
use App\Http\Controllers\Admin\TimesheetController as AdminTimesheetController;
use App\Http\Controllers\User\ManagerController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Company Holidays Routes (accessible via /admin/company-holidays)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/company-holidays', [CompanyHolidayController::class, 'index'])->name('company-holidays.index');
    Route::get('/company-holidays/create', [CompanyHolidayController::class, 'create'])->name('company-holidays.create');
    Route::post('/company-holidays', [CompanyHolidayController::class, 'store'])->name('company-holidays.store');
    Route::get('/company-holidays/{id}/edit', [CompanyHolidayController::class, 'edit'])->name('company-holidays.edit');
    Route::put('/company-holidays/{id}', [CompanyHolidayController::class, 'update'])->name('company-holidays.update');
    Route::delete('/company-holidays/{id}', [CompanyHolidayController::class, 'destroy'])->name('company-holidays.destroy');
});

// Admin Routes
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::get('/roles/{id}/status', [RoleController::class, 'showStatus'])->name('roles.status');
    Route::put('/roles/{id}/status', [RoleController::class, 'updateStatus'])->name('roles.status.update');

    Route::resource('permissions', PermissionController::class);
    Route::get('/permissions/{id}/status', [PermissionController::class, 'showStatus'])->name('permissions.status');

    Route::resource('departments', DepartmentController::class);
    Route::put('/departments/{id}/status', [DepartmentController::class, 'updateStatus'])->name('departments.status.update');

    Route::resource('designations', DesignationController::class);
    Route::get('/designations/{id}/status', [DesignationController::class, 'showStatus'])->name('designations.status');
    Route::put('/designations/{id}/status', [DesignationController::class, 'updateStatus'])->name('designations.status.update');

    Route::resource('business-units', BusinessUnitController::class);
    Route::put('/business-units/{id}/status', [BusinessUnitController::class, 'updateStatus'])->name('business-units.status.update');

    Route::resource('locations', LocationController::class);
    Route::put('/locations/{id}/status', [LocationController::class, 'updateStatus'])->name('locations.status.update');

    Route::resource('users', UserController::class);
    Route::get('/users/{id}/status', [UserController::class, 'showStatus'])->name('users.status');
    Route::get('/users/{id}/payroll', [UserController::class, 'editPayroll'])->name('users.payroll');
    Route::put('/users/{id}/payroll', [UserController::class, 'updatePayroll'])->name('users.payroll.update');

    Route::resource('clients', ClientController::class);
    Route::get('/clients/{id}/status', [ClientController::class, 'showStatus'])->name('clients.status');
    Route::get('/clients/search', [ClientController::class, 'search'])->name('clients.search');

    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{id}/status', [ProjectController::class, 'showStatus'])->name('projects.status');

    Route::prefix('projects/{projectId}')->name('projects.')->group(function () {
        Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
        Route::get('meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
        Route::post('meetings', [MeetingController::class, 'store'])->name('meetings.store');
        Route::get('meetings/{meetingId}', [MeetingController::class, 'show'])->name('meetings.show');
        Route::get('meetings/{meetingId}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
        Route::put('meetings/{meetingId}', [MeetingController::class, 'update'])->name('meetings.update');
        Route::delete('meetings/{meetingId}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
        Route::get('meetings/{meetingId}/download', [MeetingController::class, 'download'])->name('meetings.download');
        Route::post('meetings/{meetingId}/send', [MeetingController::class, 'send'])->name('meetings.send');
    });

    Route::get('/leaves/applications', [LeaveApplicationsController::class, 'index'])->name('leave.applications');
    Route::put('/leaves/applications/{id}/approve', [LeaveApplicationsController::class, 'update'])->name('leave.applications.approve');
    Route::resource('leaves', LeaveController::class)->except(['show']);
    Route::put('/leaves/{id}/status', [LeaveController::class, 'updateStatus'])->name('leaves.status.update');
    
    // Timesheet Management (Admin)
    Route::get('/timesheets', [AdminTimesheetController::class, 'adminIndex'])->name('timesheets.index');
    Route::get('/timesheets/detail', [AdminTimesheetController::class, 'adminDetail'])->name('timesheets.detail');
    Route::get('/timesheets/approve', [AdminTimesheetController::class, 'adminApprove'])->name('timesheets.approve');
    Route::get('/timesheets/approve/{id}', [AdminTimesheetController::class, 'showForApproval'])->name('timesheets.approve.show');
    Route::put('/timesheets/{id}/approve', [AdminTimesheetController::class, 'adminApproveUpdate'])->name('timesheets.approve.update');
    Route::put('/timesheets/approve/by-date', [AdminTimesheetController::class, 'approveByDate'])->name('timesheets.approve.byDate');
    
    // Team Management
    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
});


// Manager Routes
Route::prefix('manager')->middleware('auth')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    
    // Leave Management
    Route::get('/leaves', [UserLeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/apply', [UserLeaveController::class, 'apply'])->name('leaves.apply');
    Route::post('/leaves/apply', [UserLeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{id}/edit', [UserLeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('/leaves/{id}/edit', [UserLeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{id}/cancel', [UserLeaveController::class, 'cancel'])->name('leaves.cancel');
    Route::get('/leaves/approve', [ManagerController::class, 'teamLeaveHistory'])->name('leaves.approve');
    Route::get('/leaves/pending', [ManagerController::class, 'approveLeave'])->name('leaves.pending');
    Route::put('/leaves/{id}/approve', [ManagerController::class, 'updateLeave'])->name('leaves.approve.update');
    Route::put('/leaves/{id}/status', [ManagerController::class, 'updateLeave'])->name('leaves.status.update');
    Route::get('/leaves/team-history', [ManagerController::class, 'teamLeaveHistory'])->name('leaves.team.history');
    
    // Timesheet Management (own timesheet - accessible via /manager/timesheets)
    Route::get('/timesheets', [UserTimesheetController::class, 'index'])->name('timesheets.index');
    Route::get('/timesheets/apply', [UserTimesheetController::class, 'apply'])->name('timesheets.apply');
    Route::post('/timesheets', [UserTimesheetController::class, 'store'])->name('timesheets.store');
    Route::patch('/timesheets/{id}/draft', [UserTimesheetController::class, 'updateDraft'])->name('timesheets.updateDraft');
    Route::post('/timesheets/{id}/submit', [UserTimesheetController::class, 'submit'])->name('timesheets.submit');
    Route::delete('/timesheets/{id}', [UserTimesheetController::class, 'destroy'])->name('timesheets.destroy');
    
    // Timesheet Management (subordinates timesheets)
    Route::get('/timesheets/team', [ManagerController::class, 'teamTimesheets'])->name('timesheets.team');
    Route::get('/timesheets/team/detail', [ManagerController::class, 'teamTimesheetDetail'])->name('timesheets.team.detail');
    Route::get('/timesheets/approve', [ManagerController::class, 'approveTimesheet'])->name('timesheets.approve');
    Route::put('/timesheets/{id}/approve', [ManagerController::class, 'updateTimesheet'])->name('timesheets.approve.update');
    Route::put('/timesheets/approve/by-date', [ManagerController::class, 'approveByDate'])->name('timesheets.approve.byDate');
});

// Employee Routes (also accessible for reporting managers to view their own timesheets)
Route::prefix('employee')->middleware('auth')->name('employee.')->group(function () {
    Route::get('/dashboard', function () {
        $reminders = \App\Models\TimesheetReminder::getActiveRemindersForUser(auth()->id());
        return view('User.employee.dashboard', compact('reminders'));
    })->name('dashboard');
    
    // Leave Management
    Route::get('/leaves', [UserLeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/apply', [UserLeaveController::class, 'apply'])->name('leaves.apply');
    Route::post('/leaves/apply', [UserLeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{id}/edit', [UserLeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('/leaves/{id}/edit', [UserLeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{id}/cancel', [UserLeaveController::class, 'cancel'])->name('leaves.cancel');
    
    // Timesheet Management
    Route::get('/timesheets', [UserTimesheetController::class, 'index'])->name('timesheets.index');
    Route::get('/timesheets/apply', [UserTimesheetController::class, 'apply'])->name('timesheets.apply');
    Route::post('/timesheets', [UserTimesheetController::class, 'store'])->name('timesheets.store');
    Route::patch('/timesheets/{id}/draft', [UserTimesheetController::class, 'updateDraft'])->name('timesheets.updateDraft');
    Route::post('/timesheets/{id}/submit', [UserTimesheetController::class, 'submit'])->name('timesheets.submit');
    Route::delete('/timesheets/{id}', [UserTimesheetController::class, 'destroy'])->name('timesheets.destroy');
});
