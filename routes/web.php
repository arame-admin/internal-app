<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\MeetingController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::get('/roles/{id}/status', [RoleController::class, 'showStatus'])->name('roles.status');
    Route::put('/roles/{id}/status', [RoleController::class, 'updateStatus'])->name('roles.status.update');

    Route::resource('permissions', PermissionController::class);
    Route::get('/permissions/{id}/status', [PermissionController::class, 'showStatus'])->name('permissions.status');

    Route::resource('departments', DepartmentController::class);
    Route::get('/departments/{id}/status', [DepartmentController::class, 'showStatus'])->name('departments.status');

    Route::resource('users', UserController::class);
    Route::get('/users/{id}/status', [UserController::class, 'showStatus'])->name('users.status');
    Route::get('/users/{id}/payroll', [UserController::class, 'editPayroll'])->name('users.payroll');
    Route::put('/users/{id}/payroll', [UserController::class, 'updatePayroll'])->name('users.payroll.update');

    Route::resource('clients', ClientController::class);
    Route::get('/clients/{id}/status', [ClientController::class, 'showStatus'])->name('clients.status');

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

    Route::resource('leaves', LeaveController::class)->except(['show']);
    Route::get('/leaves/{id}/status', [LeaveController::class, 'showStatus'])->name('leaves.status');
    Route::get('/company-holidays', [LeaveController::class, 'holidayIndex'])->name('company-holidays.index');
    Route::get('/company-holidays/create', [LeaveController::class, 'holidayCreate'])->name('company-holidays.create');
    Route::post('/company-holidays', [LeaveController::class, 'holidayStore'])->name('company-holidays.store');
    Route::get('/company-holidays/{holiday}', [LeaveController::class, 'holidayShow'])->name('company-holidays.show');
    Route::get('/company-holidays/{holiday}/edit', [LeaveController::class, 'holidayEdit'])->name('company-holidays.edit');
    Route::put('/company-holidays/{holiday}', [LeaveController::class, 'holidayUpdate'])->name('company-holidays.update');
    Route::delete('/company-holidays/{holiday}', [LeaveController::class, 'holidayDestroy'])->name('company-holidays.destroy');
    Route::get('/company-holidays/{holiday}/status', [LeaveController::class, 'showStatus'])->name('company-holidays.status');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');