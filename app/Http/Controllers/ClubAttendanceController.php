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
        $previousAttendance = ClubAttendance::with('delinquentsPivot','delinquents')->where('club_register_id', $request->club_register_id)->orderBy('created_at','desc')->first();
        if ($previousAttendance->delinquentsPivot->isNotEmpty() && $previousAttendance->delinquents->isEmpty()) {
            foreach ($previousAttendance->delinquentsPivot as $delinquent) {
                $previousAttendance->delinquents()->create([
                    'club_attendance_id' => $previousAttendance->id,
                    'club_attendance_learner_id' => $delinquent->pivot->id,
                    'resolved' => false,
                    'link' => null,
                    'resolved_by' => null,
                    'remarks' => null,
                ]);
            }
        }
        $delinquents = AttendanceDelinquence::today($previousAttendance->id);
        $attendance = ClubAttendance::with('clubAttendanceLearner')->where('club_register_id', $request->club_register_id)->orderBy('created_at','desc')->get();
        return Inertia::render('ClubAttendanceList', [
            'attendance' => $attendance,
            'delinquents' => $delinquents,
        ]);
    }

    public function create(Request $request)
    {
        $club = ClubRegister::findOrFail($request->club_register_id);

        if ($club->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        $previousAttendance = ClubAttendance::with('delinquentsPivot','delinquents')->where('club_register_id', $request->club_register_id)->orderBy('created_at','desc')->first();
        $club = ClubRegister::with('club.learners.currentEnrollment.section')->findOrFail($request->club_register_id);
        $delinquents = AttendanceDelinquence::today($previousAttendance->id);
        return Inertia::render('ClubAttendanceCreate', [
            'club' => $club,
            'delinquents' => $delinquents,
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
        $previousAttendance = ClubAttendance::where('club_register_id', $attendance->club_register_id)
        ->where('created_at', '<', $attendance->created_at)
        ->orderBy('created_at', 'desc')
        ->first();
        $delinquents = AttendanceDelinquence::today($previousAttendance->id);
        return Inertia::render('ClubAttendanceEdit', [
            'attendance' => $attendance,
            'delinquents' => $delinquents
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

    public function resolve(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $delinquent = AttendanceDelinquence::findOrFail($request->id);
        $delinquent->resolved = true;
        $delinquent->save();
        // $clubAttendance = ClubAttendance::findOrFail($delinquent->club_attendance_id);
        return 1;
    }
}
