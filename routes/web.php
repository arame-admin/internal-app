<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('roles', RoleController::class);
Route::get('/roles/{id}/status', [RoleController::class, 'showStatus'])->name('roles.status');

Route::resource('permissions', PermissionController::class);
Route::get('/permissions/{id}/status', [PermissionController::class, 'showStatus'])->name('permissions.status');

Route::resource('departments', DepartmentController::class);
Route::get('/departments/{id}/status', [DepartmentController::class, 'showStatus'])->name('departments.status');

Route::resource('users', UserController::class);
Route::get('/users/{id}/status', [UserController::class, 'showStatus'])->name('users.status');
Route::get('/users/{id}/payroll', [UserController::class, 'editPayroll'])->name('users.payroll');
Route::put('/users/{id}/payroll', [UserController::class, 'updatePayroll'])->name('users.payroll.update');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');