<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordSetupController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MyLeaveController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MyAttendanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PersonalPanelController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\Settings\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

Route::post('logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('setup-password/{token}', [PasswordSetupController::class, 'show'])->name('password.setup');
Route::post('setup-password', [PasswordSetupController::class, 'store'])->name('password.setup.store');

Route::get('/jobs', [RecruitmentController::class, 'publicList'])->name('jobs.list');
Route::get('/jobs/{vacancy}/apply', [RecruitmentController::class, 'applicationForm'])->name('jobs.apply');
Route::post('/jobs/{vacancy}/apply', [RecruitmentController::class, 'apply'])->name('jobs.apply.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::prefix('{dptid}')->middleware('dept')->group(function () {
        Route::get('/leave/my', [MyLeaveController::class, 'index'])->name('leave.my');
        Route::get('/leave/my/create', [MyLeaveController::class, 'create'])->name('leave.my.create');
        Route::post('/leave/my', [MyLeaveController::class, 'store'])->name('leave.my.store');
        Route::get('/leave/my/{leave}/edit', [MyLeaveController::class, 'edit'])->name('leave.my.edit');
        Route::put('/leave/my/{leave}', [MyLeaveController::class, 'update'])->name('leave.my.update');
        Route::get('/attendance/my', [MyAttendanceController::class, 'index'])->name('attendance.my');
        Route::post('/attendance/check-in', [MyAttendanceController::class, 'checkIn'])->name('attendance.check-in');
        Route::post('/attendance/check-out', [MyAttendanceController::class, 'checkOut'])->name('attendance.check-out');
        Route::get('/attendance/scan/{token}', [MyAttendanceController::class, 'scan'])->name('attendance.scan');
        Route::get('/panel', [PersonalPanelController::class, 'index'])->name('panel.index');
        Route::post('/panel/upload', [PersonalPanelController::class, 'upload'])->name('panel.upload');
        Route::delete('/panel/documents/{document}', [PersonalPanelController::class, 'destroy'])->name('panel.destroy');
        Route::get('/panel/documents/{document}/download', [PersonalPanelController::class, 'download'])->name('panel.download');

        Route::middleware('admin')->group(function () {
            Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
            Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
            Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
            Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
            Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
            Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
            Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
            Route::get('/leave/{leave}/edit', [LeaveController::class, 'edit'])->name('leave.edit');
            Route::put('/leave/{leave}', [LeaveController::class, 'update'])->name('leave.update');
            Route::put('/leave/{leave}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
            Route::put('/leave/{leave}/decline', [LeaveController::class, 'decline'])->name('leave.decline');
            Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
            Route::get('/attendance/summary', [AttendanceController::class, 'summary'])->name('attendance.summary');
            Route::get('/attendance/qr', [AttendanceController::class, 'qr'])->name('attendance.qr');
            Route::post('/attendance/qr/checkout', [AttendanceController::class, 'generateCheckOutQr'])->name('attendance.qr.checkout');
            Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
            Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
            Route::post('/payroll/salaries', [PayrollController::class, 'storeSalary'])->name('payroll.salaries.store');
            Route::post('/payroll/adjustments', [PayrollController::class, 'storeAdjustment'])->name('payroll.adjustments.store');
            Route::post('/payroll/calculate', [PayrollController::class, 'calculate'])->name('payroll.calculate');
            Route::get('/payroll/{payroll}/slip', [PayrollController::class, 'slip'])->name('payroll.slip');
            Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
            Route::get('/recruitment/vacancies/create', [RecruitmentController::class, 'create'])->name('recruitment.vacancies.create');
            Route::post('/recruitment/vacancies', [RecruitmentController::class, 'store'])->name('recruitment.vacancies.store');
            Route::get('/recruitment/vacancies/{vacancy}/edit', [RecruitmentController::class, 'edit'])->name('recruitment.vacancies.edit');
            Route::put('/recruitment/vacancies/{vacancy}', [RecruitmentController::class, 'update'])->name('recruitment.vacancies.update');
            Route::get('/recruitment/vacancies/{vacancy}/applications', [RecruitmentController::class, 'applications'])->name('recruitment.applications');
            Route::put('/recruitment/applications/{application}', [RecruitmentController::class, 'updateApplication'])->name('recruitment.applications.update');
            Route::get('/recruitment/applications/{application}/resume', [RecruitmentController::class, 'downloadResume'])->name('recruitment.applications.resume');
            Route::post('/recruitment/applications/{application}/interviews', [RecruitmentController::class, 'storeInterview'])->name('recruitment.interviews.store');
        });
    });

    Route::middleware(['admin', 'superuser'])->group(function () {
        Route::get('/settings', fn () => view('settings.index'))->name('settings.index');
        Route::resource('/settings/departments', DepartmentController::class)->except(['show'])->names([
            'index' => 'settings.departments.index',
            'create' => 'settings.departments.create',
            'store' => 'settings.departments.store',
            'edit' => 'settings.departments.edit',
            'update' => 'settings.departments.update',
            'destroy' => 'settings.departments.destroy',
        ]);
    });
});
