<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Disabled - delete account feature removed
    
    // Attendance routes
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [App\Http\Controllers\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [App\Http\Controllers\AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users/attendances', [App\Http\Controllers\Admin\DashboardController::class, 'userAttendances'])->name('users.attendances');
        Route::get('/users/attendances/export', [App\Http\Controllers\Admin\DashboardController::class, 'exportAttendances'])->name('users.attendances.export');
        Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        
        // Holidays routes
        Route::get('/holidays', [App\Http\Controllers\Admin\HolidayController::class, 'index'])->name('holidays.index');
        Route::get('/holidays/create', [App\Http\Controllers\Admin\HolidayController::class, 'create'])->name('holidays.create');
        Route::post('/holidays', [App\Http\Controllers\Admin\HolidayController::class, 'store'])->name('holidays.store');
        Route::delete('/holidays/{holiday}', [App\Http\Controllers\Admin\HolidayController::class, 'destroy'])->name('holidays.destroy');
        Route::patch('/holidays/{holiday}/toggle', [App\Http\Controllers\Admin\HolidayController::class, 'toggle'])->name('holidays.toggle');
    });
});

require __DIR__.'/auth.php';
