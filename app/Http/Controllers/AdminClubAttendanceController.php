<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;

class AdminClubAttendanceController extends Controller
{
    public function index()
    {
        $attendance = ClubAttendance::with(['clubRegister.club', 'clubAttendanceLearner'])->orderBy('date', 'desc')->get();
        return Inertia::render('admin/ClubAttendance', [
            'attendance' => $attendance
        ]);
    }
}
