<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::middleware('auth')->group(function () {
    Route::get('/enrollment', [EnrollmentController::class, 'index'])->name('enrollment');
    Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');
    Route::post('/enrollment/bulk', [EnrollmentController::class, 'storeBulk'])->name('enrollment.bulk');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/club/members', [ClubController::class, 'membersList'])->name('club.members');
    Route::get('/clubs', [ClubController::class, 'clubsList'])->name('club.list');
    Route::post('/club/register', [ClubController::class, 'registerMember'])->name('club.register');
    Route::post('/club/unregister', [ClubController::class, 'unregisterMember'])->name('club.unregister');
    Route::get('/club-management', function () {
        return Inertia::render('Club');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
