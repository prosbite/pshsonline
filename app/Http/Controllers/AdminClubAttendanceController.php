<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDelinquence;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;
use App\Models\Learner;
use Carbon\Carbon;

class AdminClubAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        // dd($date);
        $attendanceDates = ClubAttendance::select('date')->distinct()->orderBy('date', 'desc')->get()->pluck('date');
        if($attendanceDates->count() > 0 && !$request->date){
            $date = $attendanceDates->first();
        }
        $attendance = ClubAttendance::with(['clubRegister.club', 'clubAttendanceLearner'])->orderBy('date', 'desc')->where('date', $date)->get();
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
        // $attendance = ClubAttendance::with(['delinquents.clubAttendanceLearner.learner.currentEnrollment.section.gradeLevel','delinquents.clubAttendanceLearner.clubAttendance.clubRegister.club'])->orderBy('date', 'desc')->get()->where('date', $date);
        $delinquents = AttendanceDelinquence::select('attendance_delinquences.*')
        ->join('club_attendances', 'attendance_delinquences.club_attendance_id', '=', 'club_attendances.id')
        ->where('club_attendances.date', $date)
        ->orderBy('club_attendances.date', 'desc')
        ->with([
            'clubAttendance.clubRegister.club',
            'clubAttendanceLearner.learner.currentEnrollment.section.gradeLevel',
        ])
        ->get();
        return Inertia::render('admin/ClubAttendanceDelinquents', [
            'delinquents' => $delinquents,
            'attendanceDates' => $attendanceDates,
            'date' => $date
        ]);
    }

    public function deleteAttendance(Request $request)
    {
        $attendance = ClubAttendance::find($request->id);
        $attendance->delete();
        return redirect()->back();
    }

    public function infractions() {
        // $infractions = AttendanceDelinquence::with(['clubAttendance.clubRegister.club', 'clubAttendanceLearner.learner.currentEnrollment.section.gradeLevel'])->get();
        $infractions = Learner::whereHas('clubAttendance', function ($query) {
            // This closure constrains the join (or WHERE EXISTS) on the pivot table.
            $query->whereIn('status', [
                'excused_absence',
                'unexcused_absence',
                'cutting_classes'
            ]);
        })->with(['clubAttendance' => function ($query) {
            // OPTIONAL: This part ensures only the filtered attendance records are eager-loaded.
            // If you omit this, ALL attendance records for the matching learners will be loaded,
            // which is probably not what you want.
            $query->whereIn('status', [
                'excused_absence',
                'unexcused_absence',
                'cutting_classes'
            ]);
        },'clubAttendance.clubRegister.club'])->orderBy('last_name', 'asc')->get();
        return Inertia::render('admin/ClubAttendanceInfractions', [
            'infractions' => $infractions,
        ]);
    }
}
