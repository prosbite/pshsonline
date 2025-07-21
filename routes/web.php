<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminClubController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClubAttendanceController;
use Illuminate\Support\Facades\Route;
use App\Models\ClubRegister;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/club-management', function () {
        return Inertia::render('Club');
    });
    Route::get('/change-password', function () {
        return Inertia::render('ChangePassword');
    })->name('change-password');
});

// Admin Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/users', [ProfileController::class, 'index'])->name('users');
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');

    Route::get('/enrollment', [EnrollmentController::class, 'index'])->name('enrollment');
    Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');
    Route::post('/enrollment/bulk', [EnrollmentController::class, 'storeBulk'])->name('enrollment.bulk');
    Route::get('/student/search', [StudentController::class, 'search'])->name('student.search');
    Route::post('/learner/update', [StudentController::class, 'update'])->name('admin.learner.update');

    Route::get('/club/members', [ClubController::class, 'membersList'])->name('club.members');
    Route::get('/clubs', [AdminClubController::class, 'index'])->name('admin.club.list');
    Route::get('/club/{club}', [AdminClubController::class, 'show'])->name('admin.club.show');
    Route::post('/club/register', [ClubController::class, 'registerMember'])->name('club.register');
    Route::post('/club/unregister', [AdminClubController::class, 'unregisterMember'])->name('admin.club.unregister');
    Route::post('/club/update', [ClubController::class, 'updateClub'])->name('club.update');
});

// Club Adviser Routes
Route::middleware('auth')->group(function () {
    Route::get('/club/members', [ClubController::class, 'membersList'])->name('club.members');
    Route::get('/club/{club_register_id}/attendance', [ClubAttendanceController::class, 'index'])->name('club.attendance');
    Route::post('/club/unregister', [ClubController::class, 'unregisterMember'])->name('club.unregister');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/change-password', function () {
        return Inertia::render('ChangePassword');
    })->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password.store');
});

require __DIR__.'/auth.php';
