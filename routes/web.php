<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

Route::post('logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/leave/my', fn () => view('leave.my'))->name('leave.my');
    Route::get('/attendance/my', fn () => view('attendance.my'))->name('attendance.my');

    Route::middleware('admin')->group(function () {
        Route::get('/employees', fn () => view('employees.index'))->name('employees.index');
        Route::get('/leave', fn () => view('leave.index'))->name('leave.index');
        Route::get('/attendance', fn () => view('attendance.index'))->name('attendance.index');
        Route::get('/recruitment', fn () => view('recruitment.index'))->name('recruitment.index');
        Route::get('/settings', fn () => view('settings.index'))->name('settings.index');
    });
});
