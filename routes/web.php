<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkExperienceController;
use App\Http\Controllers\PersonalDataSheetsController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\EligibilityController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\VoluntaryWorkController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\DistinctionController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\EmploymentController;
use App\Http\Controllers\GovernmentIdController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\MyDocumentController;
use App\Http\Controllers\DocumentManagementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BackgroundInfoController;

Route::get('/', function () {
    return view('welcome');
});

// Admin login routes (access during maintenance)
Route::get('/admin-login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin-login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

// Regular login route (redirects to admin login during maintenance)
Route::get('/login', function () {
    if (\App\Models\Setting::get('maintenance_mode', false)) {
        return redirect()->route('admin.login');
    }
    return view('auth.login');
})->name('login');

// Maintenance notice route
Route::get('/maintenance', [AdminLoginController::class, 'maintenanceNotice'])->name('maintenance.notice');

// Settings routes
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [SettingsController::class, 'testEmail'])->name('settings.test-email');
    Route::get('/maintenance-preview', function () {
        return view('errors.maintenance', [
            'message' => setting('maintenance_message', 'Under maintenance'),
            'companyName' => setting('company_name', 'HRMIS'),
        ]);
    })->name('maintenance.preview');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/update', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/my-profile', [MyProfileController::class, 'show'])->name('myprofile.show');
    Route::get('/my-profile/edit/personaldata/{userId?}', [MyProfileController::class, 'editPersonalData'])->name('myprofile.editPersonalData');
    Route::put('/my-profile/edit/personaldata/{userId?}', [MyProfileController::class, 'updatePersonalData'])->name('myprofile.updatePersonalData');

    Route::get('/my-profile/create/familydetails/{userId?}', [MyProfileController::class, 'createFamilyDetails'])->name('myprofile.createFamilyDetails');
    Route::post('/my-profile/store/familydetails/{userId?}', [MyProfileController::class, 'storeFamilyDetails'])->name('family-background.store');
    Route::get('/my-profile/edit/familydetails/{userId?}', [MyProfileController::class, 'editFamilyDetails'])->name('myprofile.editFamilyDetails');
    Route::put('/my-profile/update/familydetails/{userId?}', [MyProfileController::class, 'updateFamilyDetails'])->name('myprofile.udpateFamilyDetails');

    Route::get('/my-profile/create/children/{userId?}', [ChildrenController::class, 'create'])->name('myprofile.createChild');
    Route::post('/my-profile/create/children/{userId?}', [ChildrenController::class, 'store'])->name('myprofile.storeChildren');
    Route::get('/my-profile/edit/children/{userId?}', [ChildrenController::class, 'store'])->name('myprofile.editChild');
    Route::get('/myprofile/children/{id}/edit-data', [ChildrenController::class, 'getEditData'])->name('myprofile.getChildData');
    Route::put('/myprofile/children/{id}', [ChildrenController::class, 'update'])->name('myprofile.updateChildData');
    Route::delete('/myprofile/children/{id}', [ChildrenController::class, 'destroy'])->name('myprofile.deleteChildData');

    // Education routes
    Route::get('/myprofile/education/{id}/edit-data', [EducationController::class, 'getEditData'])->name('myprofile.education.getData');
    Route::post('/myprofile/education', [EducationController::class, 'store'])->name('myprofile.education.store');
    Route::put('/myprofile/education/{id}', [EducationController::class, 'update'])->name('myprofile.education.update');
    Route::delete('/myprofile/education/{id}', [EducationController::class, 'destroy'])->name('myprofile.education.destroy');

    //work experience
    Route::get('/myprofile/work/{id}/edit-data', [WorkExperienceController::class, 'getEditData'])->name('myprofile.work.getData');
    Route::post('/myprofile/work', [WorkExperienceController::class, 'store'])->name('myprofile.work.store');
    Route::put('/myprofile/work/{id}', [WorkExperienceController::class, 'update'])->name('myprofile.work.update');
    Route::delete('/myprofile/work/{id}', [WorkExperienceController::class, 'destroy'])->name('myprofile.work.destroy');

    //eligibilities
    Route::get('/myprofile/eligibility/{id}/edit-data', [EligibilityController::class, 'getEditData'])->name('myprofile.eligibility.getData');
    Route::post('/myprofile/eligibility', [EligibilityController::class, 'store'])->name('myprofile.eligibility.store');
    Route::put('/myprofile/eligibility/{id}', [EligibilityController::class, 'update'])->name('myprofile.eligibility.update');
    Route::delete('/myprofile/eligibility/{id}', [EligibilityController::class, 'destroy'])->name('myprofile.eligibility.destroy');

    //trainings
    Route::get('/myprofile/training/{id}/edit-data', [TrainingController::class, 'getEditData'])->name('myprofile.training.getData');
    Route::post('/myprofile/training', [TrainingController::class, 'store'])->name('myprofile.training.store');
    Route::put('/myprofile/training/{id}', [TrainingController::class, 'update'])->name('myprofile.training.update');
    Route::delete('/myprofile/training/{id}', [TrainingController::class, 'destroy'])->name('myprofile.training.destroy');

    //voluntary works
    Route::get('/myprofile/voluntary-work/{id}/edit-data', [VoluntaryWorkController::class, 'getEditData'])->name('myprofile.voluntary-work.getData');
    Route::post('/myprofile/voluntary-work', [VoluntaryWorkController::class, 'store'])->name('myprofile.voluntary-work.store');
    Route::put('/myprofile/voluntary-work/{id}', [VoluntaryWorkController::class, 'update'])->name('myprofile.voluntary-work.update');
    Route::delete('/myprofile/voluntary-work/{id}', [VoluntaryWorkController::class, 'destroy'])->name('myprofile.voluntary-work.destroy');

    //skills
    Route::get('/myprofile/skill/{id}/edit-data', [SkillController::class, 'getEditData'])->name('myprofile.skill.getData');
    Route::post('/myprofile/skill', [SkillController::class, 'store'])->name('myprofile.skill.store');
    Route::put('/myprofile/skill/{id}', [SkillController::class, 'update'])->name('myprofile.skill.update');
    Route::delete('/myprofile/skill/{id}', [SkillController::class, 'destroy'])->name('myprofile.skill.destroy');
    
    //distinctions
    Route::get('/myprofile/distinction/{id}/edit-data', [DistinctionController::class, 'getEditData'])->name('myprofile.distinction.getData');
    Route::post('/myprofile/distinction', [DistinctionController::class, 'store'])->name('myprofile.distinction.store');
    Route::put('/myprofile/distinction/{id}', [DistinctionController::class, 'update'])->name('myprofile.distinction.update');
    Route::delete('/myprofile/distinction/{id}', [DistinctionController::class, 'destroy'])->name('myprofile.distinction.destroy');

    //organizations
    Route::get('/myprofile/organization/{id}/edit-data', [OrganizationController::class, 'getEditData'])->name('myprofile.organization.getData');
    Route::post('/myprofile/organization', [OrganizationController::class, 'store'])->name('myprofile.organization.store');
    Route::put('/myprofile/organization/{id}', [OrganizationController::class, 'update'])->name('myprofile.organization.update');
    Route::delete('/myprofile/organization/{id}', [OrganizationController::class, 'destroy'])->name('myprofile.organization.destroy');

    //references
    Route::get('/myprofile/reference/{id}/edit-data', [ReferenceController::class, 'getEditData'])->name('myprofile.reference.getData');
    Route::post('/myprofile/reference', [ReferenceController::class, 'store'])->name('myprofile.reference.store');
    Route::put('/myprofile/reference/{id}', [ReferenceController::class, 'update'])->name('myprofile.reference.update');
    Route::delete('/myprofile/reference/{id}', [ReferenceController::class, 'destroy'])->name('myprofile.reference.destroy');

    //employment
    Route::prefix('myprofile')->name('myprofile.')->middleware(['auth'])->group(function () {
        Route::get('/employment', [EmploymentController::class, 'index'])->name('employment');
        Route::get('/employment/{id}/edit-data', [EmploymentController::class, 'getEditData'])->name('employment.getData');
        Route::post('/employment', [EmploymentController::class, 'store'])->name('employment.store');
        Route::put('/employment/{id}', [EmploymentController::class, 'update'])->name('employment.update');
    });

    //backgroudn iformations
    Route::get('/background', [BackgroundInfoController::class, 'index'])->name('myprofile.background.index');
    Route::get('/background/create', [BackgroundInfoController::class, 'create'])->name('myprofile.background.create');
    Route::post('/background', [BackgroundInfoController::class, 'store'])->name('myprofile.background.store');
    Route::get('/background/{backgroundInfo}', [BackgroundInfoController::class, 'show'])->name('myprofile.background.show');
    Route::get('/background/{backgroundInfo}/edit', [BackgroundInfoController::class, 'edit'])->name('myprofile.background.edit');
    Route::put('/background/{backgroundInfo}', [BackgroundInfoController::class, 'update'])->name('myprofile.background.update');
    Route::delete('/background/{backgroundInfo}', [BackgroundInfoController::class, 'destroy'])->name('myprofile.background.destroy');
    
    // My Background (redirects to user's own)
    Route::get('/my-background', [BackgroundInfoController::class, 'myBackground'])->name('myprofile.background.my');

    //documents management routes
    Route::get('/my-documents', [MyDocumentController::class, 'index'])
    ->name('mydocuments.index');
    Route::get('/my-documents/create', [MyDocumentController::class, 'create'])->name('mydocuments.create');
    Route::post('/my-documents/store', [MyDocumentController::class, 'store'])->name('mydocuments.store');
    Route::get('/my-documents/{document}/edit', [MyDocumentController::class, 'edit'])->name('mydocuments.edit');
    Route::put('/my-documents/{document}', [MyDocumentController::class, 'update'])->name('mydocuments.update');
    Route::delete('/my-documents/{document}', [MyDocumentController::class, 'destroy'])->name('mydocuments.destroy');
    Route::get('/my-documents/{document}/download', [MyDocumentController::class, 'download'])->name('mydocuments.download');

    Route::get('/attendance/my-timesheet', [SettingsController::class, 'index'])->name('attendance.my-timesheet');
    Route::get('/leave/my-requests', [SettingsController::class, 'index'])->name('leave.my-requests');
    Route::get('/payroll/my-payslips', [SettingsController::class, 'index'])->name('payroll.my-payslips');
    Route::get('/documents/my-documents', [SettingsController::class, 'index'])->name('documents.my-documents');

    Route::get('/employees', [SettingsController::class, 'index'])->name('employees.index');

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

    Route::resource('pds', PersonalDataSheetsController::class);
});

Route::middleware(['auth', 'role:admin|hr'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/roles', [SettingsController::class, 'roles'])->name('settings.roles');

    Route::resource('/settings/users', UsersController::class);
    Route::post('/users/{user}/enable', [UsersController::class, 'enable'])->name('users.enable');
    Route::post('/users/{user}/disable', [UsersController::class, 'disable'])->name('users.disable');
    Route::put('/users/{user}/change-role', [UsersController::class, 'changeRole'])->name('users.changeRole');

    //settings positions
    Route::resource('/settings/positions', PositionsController::class);
    Route::post('/positions/{position}/enable', [PositionsController::class, 'enable'])->name('positions.enable');
    Route::post('/positions/{position}/disable', [PositionsController::class, 'disable'])->name('positions.disable');

    //settings departments
    Route::resource('/settings/departments', DepartmentsController::class);
    Route::post('/departments/{department}/enable', [DepartmentsController::class, 'enable'])->name('departments.enable');
    Route::post('/departments/{department}/disable', [DepartmentsController::class, 'disable'])->name('departments.disable');

    //roles
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    //settings
    Route::get('/settings/system', [SettingsController::class, 'index'])->name('settings.system');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [SettingsController::class, 'testEmail'])->name('settings.test-email');

    //governement ids
    Route::get('/myprofile/government-id/{id}/edit-data', [GovernmentIdController::class, 'getEditData'])->name('myprofile.government-id.getData');
    Route::post('/myprofile/government-id', [GovernmentIdController::class, 'store'])->name('myprofile.government-id.store');
    Route::put('/myprofile/government-id/{id}', [GovernmentIdController::class, 'update'])->name('myprofile.government-id.update');

    //address
    Route::get('/myprofile/address/{id}/edit-data', [AddressController::class, 'getEditData'])->name('myprofile.address.getData');
    Route::post('/myprofile/address', [AddressController::class, 'store'])->name('myprofile.address.store');
    Route::put('/myprofile/address/{id}', [AddressController::class, 'update'])->name('myprofile.address.update');
    Route::delete('/myprofile/address/{id}', [AddressController::class, 'destroy'])->name('myprofile.address.destroy');

    //payroll
    Route::get('/payroll/benefits', [SettingsController::class, 'index'])->name('payroll.benefits');
    
    //recruitment
    Route::get('/recruitment/jobs', [SettingsController::class, 'index'])->name('recruitment.jobs');
    Route::get('/recruitment/applications', [SettingsController::class, 'index'])->name('recruitment.applications');
    Route::get('/recruitment/candidates', [SettingsController::class, 'index'])->name('recruitment.candidates');
    Route::get('/recruitment/interviews', [SettingsController::class, 'index'])->name('recruitment.interviews');

    //performance
    Route::get('/performance/reviews', [SettingsController::class, 'index'])->name('performance.reviews');
    Route::get('/performance/goals', [SettingsController::class, 'index'])->name('performance.goals');
    Route::get('/performance/feedback', [SettingsController::class, 'index'])->name('performance.feedback');
    Route::get('/performance/training', [SettingsController::class, 'index'])->name('performance.training');

    //background information admin
    Route::get('/admin/background/user/{userId}', [BackgroundInfoController::class, 'userBackground'])->name('admin.background.user');
    Route::get('/admin/background/statistics', [BackgroundInfoController::class, 'statistics'])->name('admin.background.statistics');

    //reports
    Route::get('/reports/attendance', [SettingsController::class, 'index'])->name('reports.index');

    //employee documents  management routes
    Route::get('employee/documents', [DocumentManagementController::class, 'index'])->name('employee.documents.index');
    Route::get('employee/documents/{document}/edit', [DocumentManagementController::class, 'edit'])->name('employee.documents.edit');
    Route::put('employee/documents/{document}', [DocumentManagementController::class, 'update'])->name('employee.documents.update');
    Route::post('employee/documents/{document}/approve', [DocumentManagementController::class, 'approve'])->name('employee.documents.approve');
    Route::post('employee/documents/{document}/reject', [DocumentManagementController::class, 'reject'])->name('employee.documents.reject');
    Route::delete('employee/documents/{document}', [DocumentManagementController::class, 'destroy'])->name('employee.documents.destroy');
    Route::get('employee/documents/{document}/download', [DocumentManagementController::class, 'download'])->name('employee.documents.download');
    Route::get('employee/documents/export', [DocumentManagementController::class, 'export'])->name('employee.documents.export');
    Route::get('employee/documents/report', [DocumentManagementController::class, 'report'])->name('employee.documents.report');
    Route::get('employee/{document}/preview', [DocumentManagementController::class, 'preview'])->name('employee.documents.preview');
    Route::get('employee/documents/stream/{document}', [DocumentManagementController::class, 'stream'])
    ->name('employee.documents.stream');

    Route::post('documents/bulk-approve', [DocumentManagementController::class, 'bulkApprove'])->name('employee.documents.bulk-approve');
    Route::post('documents/bulk-reject', [DocumentManagementController::class, 'bulkReject'])->name('employee.documents.bulk-reject');
    Route::post('documents/bulk-delete', [DocumentManagementController::class, 'bulkDelete'])->name('employee.documents.bulk-delete');

    //document settings
    Route::get('employee/documents/settings', [DocumentManagementController::class, 'settings'])->name('employee.documents.settings');


    //company documents management routes
    Route::get('company/documents', [DocumentManagementController::class, 'index'])->name('company.documents.index');
    Route::get('company/documents/{document}/edit', [DocumentManagementController::class, 'companyEdit'])->name('company.documents.edit');
    Route::put('company/documents/{document}', [DocumentManagementController::class, 'companyUpdate'])->name('company.documents.update');
    Route::post('company/documents/{document}/approve', [DocumentManagementController::class, 'companyApprove'])->name('company.documents.approve');
    Route::post('company/documents/{document}/reject', [DocumentManagementController::class, 'companyReject'])->name('company.documents.reject');
    Route::delete('company/documents/{document}', [DocumentManagementController::class, 'companyDestroy'])->name('company.documents.destroy');
    Route::get('company/documents/{document}/download', [DocumentManagementController::class, 'companyDownload'])->name('documents.company.download');
    Route::get('company/documents/export', [DocumentManagementController::class, 'companyExport'])->name('documents.company.export');
    Route::get('company/documents/report', [DocumentManagementController::class, 'companyReport'])->name('documents.company.report');

    //document templates management routes
    Route::get('templates/documents', [DocumentManagementController::class, 'index'])->name('templates.documents.index');

    //view epmployee profile
    Route::get('employee/profile/{employee}', [DocumentManagementController::class, 'index'])->name('employees.show');

    // Maintenance mode routes
    Route::get('/maintenance', [SettingsController::class, 'maintenance'])->name('settings.maintenance');
    Route::get('/maintenance-status', function () {
        return response()->json([
            'maintenance' => \App\Models\Setting::get('maintenance_mode', false)
        ]);
    })->name('maintenance.status');

    Route::get('/maintenance-preview', function () {
        $message = setting('maintenance_message', 'We are currently undergoing maintenance.');
        $companyName = setting('company_name', 'HRMIS');
        $contactEmail = setting('company_email', '');
        $contactPhone = setting('company_phone', '');
        
        return view('errors.maintenance', compact('message', 'companyName', 'contactEmail', 'contactPhone'));
    })->name('maintenance.preview')->middleware(['auth', 'admin']);

    //empolyee management routes
    Route::prefix('employees')->name('employees.')->group(function() {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::post('/store', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
