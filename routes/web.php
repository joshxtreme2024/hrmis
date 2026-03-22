<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PositionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/update', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/my-profile', [MyProfileController::class, 'show'])->name('myprofile.show');
    Route::get('/my-profile/edit/{userId?}', [MyProfileController::class, 'edit'])->name('myprofile.edit');

    Route::get('/attendance/my-timesheet', [SettingsController::class, 'index'])->name('attendance.my-timesheet');
    Route::get('/leave/my-requests', [SettingsController::class, 'index'])->name('leave.my-requests');
    Route::get('/payroll/my-payslips', [SettingsController::class, 'index'])->name('payroll.my-payslips');
    Route::get('/documents/my-documents', [SettingsController::class, 'index'])->name('documents.my-documents');

    Route::get('/employees', [SettingsController::class, 'index'])->name('employees.index');
    Route::get('/departments', [SettingsController::class, 'index'])->name('departments.index');

    Route::get('/employment-types', [SettingsController::class, 'index'])->name('employment-types.index');
    
    Route::get('/attendance/daily', [SettingsController::class, 'index'])->name('attendance.daily');
    Route::get('/attendance/timesheets', [SettingsController::class, 'index'])->name('attendance.timesheets');
    Route::get('/attendance/overtime', [SettingsController::class, 'index'])->name('overtime.index');
    Route::get('/shifts', [SettingsController::class, 'index'])->name('shifts.index');

    Route::get('/leave/requests', [SettingsController::class, 'index'])->name('leave.requests');
    Route::get('/leave/calendar', [SettingsController::class, 'index'])->name('leave.calendar');
    Route::get('/leave/types', [SettingsController::class, 'index'])->name('leave.types');
    Route::get('/leave/my-requests', [SettingsController::class, 'index'])->name('leave.my-requests');

    Route::get('/payroll/salaries', [SettingsController::class, 'index'])->name('payroll.salaries');
    Route::get('/payroll/payslips', [SettingsController::class, 'index'])->name('payroll.payslips');
    Route::get('/payroll/tax', [SettingsController::class, 'index'])->name('payroll.tax');
    Route::get('/payroll/benefits', [SettingsController::class, 'index'])->name('payroll.benefits');

    Route::get('/payroll/benefits', [SettingsController::class, 'index'])->name('reports.index');

    
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/users', [SettingsController::class, 'users'])->name('settings.users');
    Route::get('/settings/roles', [SettingsController::class, 'roles'])->name('settings.roles');

    Route::get('/settings/positions', [PositionsController::class, 'index'])->name('positions.index');
    Route::get('/settings/positions/show', [PositionsController::class, 'index'])->name('positions.show');
    Route::get('/settings/positions/create', [PositionsController::class, 'create'])->name('positions.create');
    Route::get('/settings/positions/edit/{position}', [PositionsController::class, 'create'])->name('positions.edit');
    Route::post('/settings/positions/create', [PositionsController::class, 'store'])->name('positions.store');
    Route::post('/settings/positions/delete/{position}', [PositionsController::class, 'store'])->name('positions.destroy');

    Route::get('/settings/system', [SettingsController::class, 'system'])->name('settings.system');
});

require __DIR__.'/auth.php';
