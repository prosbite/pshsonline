<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;
use App\Models\ClubRegister;
use App\Models\AttendanceDelinquence;
use App\Models\SchoolYear;

class ClubAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $club = ClubRegister::findOrFail($request->club_register_id);

        if ($club->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        $previousAttendance = ClubAttendance::with('delinquents')->where('club_register_id', $request->club_register_id)->orderBy('created_at','desc')->first();
        if ($previousAttendance) {
            $delinquents = AttendanceDelinquence::today($previousAttendance->id);
            dd($delinquents);
        }
        $attendance = ClubAttendance::with('clubAttendanceLearner')->where('club_register_id', $request->club_register_id)->orderBy('created_at','desc')->get();
        return Inertia::render('ClubAttendanceList', [
            'attendance' => $attendance,
        ]);
    }

    public function create(Request $request)
    {
        $club = ClubRegister::findOrFail($request->club_register_id);

        if ($club->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        $club = ClubRegister::with('club.learners.currentEnrollment.section')->findOrFail($request->club_register_id);
        return Inertia::render('ClubAttendanceCreate', [
            'club' => $club,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'club_register_id' => 'required',
            'date' => 'required',
            'activity' => 'required',
            'members' => 'required|array',
        ]);
        $request['school_year_id'] = SchoolYear::current()->id;
        $club = ClubRegister::findOrFail($request->club_register_id);
        if ($club->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        $clubAttendance = ClubAttendance::create($request->all());
        $cleanMembers = collect($request->members)->mapWithKeys(function ($member, $index) use ($clubAttendance) {
            return [
                $index => [
                    'club_attendance_id' => $clubAttendance->id,
                    'learner_id' => $member['learner_id'],
                    'status' => $member['status'] ?? 'absent',
                    'remarks' => $member['remarks'] ?? null,
                ],
            ];
        })->toArray();
        $clubAttendance->clubAttendanceLearner()->attach($cleanMembers);
        return redirect()->route('club.attendance', ['club_register_id' => $club->id]);
    }
    public function show(Request $request)
    {
        $attendance = ClubAttendance::with(['clubRegister.club', 'clubAttendanceLearner'])->findOrFail($request->attendance_id);
        return Inertia::render('ClubAttendanceShow', [
            'attendance' => $attendance,
        ]);
    }
    public function edit(Request $request)
    {
        $attendance = ClubAttendance::with(['clubRegister.club', 'clubAttendanceLearner'])->findOrFail($request->attendance_id);
        return Inertia::render('ClubAttendanceEdit', [
            'attendance' => $attendance,
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'attendance.club_register_id' => 'required',
            'attendance.date' => 'required',
            'attendance.activity' => 'required',
            'attendance.club_attendance_learner' => 'required|array',
        ]);
        $request['school_year_id'] = SchoolYear::current()->id;
        $club = ClubRegister::findOrFail($request->attendance['club_register_id']);
        if ($club->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        $clubAttendance = ClubAttendance::findOrFail($request->attendance['id']);
        $clubAttendance->update($request->attendance);
        $cleanMembers = collect($request->attendance['club_attendance_learner'])->mapWithKeys(function ($member, $index) use ($clubAttendance) {
            return [
                $index => [
                    'club_attendance_id' => $clubAttendance->id,
                    'learner_id' => $member['id'],
                    'status' => $member['pivot']['status'] ?? 'absent',
                    'remarks' => $member['pivot']['remarks'] ?? null,
                ],
            ];
        })->toArray();
        $clubAttendance->clubAttendanceLearner()->sync($cleanMembers);
        return redirect()->route('club.attendance', ['club_register_id' => $club->id]);
    }
}
