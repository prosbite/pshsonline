<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Learner;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminAttendanceController extends Controller
{
    public function absentStudents(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // $absentLearners = Learner::with(['currentEnrollment.section.gradeLevel'])
        // ->whereHas('clubAttendance', function ($query) use ($today) {
        //     $query->whereDate('club_attendances.date', $today)
        //           ->where('club_attendance_learner.status', 'absent');
        // })->get();
        $absentLearners = DB::table('learners')
        ->join('club_attendance_learner', 'club_attendance_learner.learner_id', '=', 'learners.id')
        ->join('club_attendances', 'club_attendances.id', '=', 'club_attendance_learner.club_attendance_id')
        ->join('club_registers', 'club_registers.id', '=', 'club_attendances.club_register_id')
        ->join('clubs', 'clubs.id', '=', 'club_registers.club_id')
        ->join('enrollments', 'enrollments.learner_id', '=', 'learners.id')
        ->join('sections', 'sections.id', '=', 'enrollments.section_id')
        ->join('grade_levels', 'grade_levels.id', '=', 'sections.grade_level_id')
        ->whereDate('club_attendances.date', $today)
        ->where('club_attendance_learner.status', 'absent')
        ->select(
            'learners.id as learner_id',
            'learners.first_name',
            'learners.last_name',
            'learners.gender',
            'learners.middle_name',
            'clubs.name as club_name',
            'grade_levels.grade_level as grade',
            'sections.section_name as section',
            'club_attendance_learner.status as status',
        )
        ->orderBy('sections.section_name', 'asc')
        ->get();
        return Inertia::render('admin/ClubAttendanceDelinquents', [
            'learners' => $absentLearners,
        ]);
    }
}
