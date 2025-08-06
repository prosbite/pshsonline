<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;
use Carbon\Carbon;

class AdminClubAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        $attendanceDates = ClubAttendance::select('date')->distinct()->orderBy('date', 'desc')->get()->pluck('date');
        if($attendanceDates->count() > 0 && !$request->date){
            $date = $attendanceDates->first();
        }
        $attendance = ClubAttendance::with(['clubRegister.club', 'clubAttendanceLearner'])->orderBy('date', 'desc')->get()->where('date', $date);
        // dd($attendance);
        return Inertia::render('admin/ClubAttendance', [
            'attendance' => $attendance->toArray(),
            'attendanceDates' => $attendanceDates,
            'date' => $date
        ]);
    }

    public function delinquents(Request $request)
    {
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        $attendanceDates = ClubAttendance::select('date')->distinct()->orderBy('date', 'desc')->get()->pluck('date');
        if($attendanceDates->count() > 0 && !$request->date){
            $date = $attendanceDates->first();
        }
        $attendance = ClubAttendance::with(['delinquents.clubAttendanceLearner.learner.currentEnrollment.section.gradeLevel','delinquents.clubAttendanceLearner.clubAttendance.clubRegister.club'])->orderBy('date', 'desc')->get()->where('date', $date);
        return Inertia::render('admin/ClubAttendanceDelinquents', [
            'attendance' => $attendance,
            'attendanceDates' => $attendanceDates,
            'date' => $date
        ]);
    }
}
