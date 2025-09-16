<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;
use App\Models\ClubRegister;
use App\Models\AttendanceDelinquence;
use App\Models\SchoolYear;
use App\Models\Learner;

class ClubAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $club = ClubRegister::findOrFail($request->club_register_id);

        if ($club->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        // $previousAttendance = ClubAttendance::with('delinquentsPivot','delinquents')->where('club_register_id', $request->club_register_id)->orderBy('created_at','desc')->first();
        // if ($previousAttendance->delinquentsPivot->isNotEmpty() && $previousAttendance->delinquents->isEmpty()) {
        //     foreach ($previousAttendance->delinquentsPivot as $delinquent) {
        //         $previousAttendance->delinquents()->create([
        //             'club_attendance_id' => $previousAttendance->id,
        //             'club_attendance_learner_id' => $delinquent->pivot->id,
        //             'resolved' => false,
        //             'link' => null,
        //             'resolved_by' => null,
        //             'remarks' => null,
        //         ]);
        //     }
        // }
        // $delinquents = AttendanceDelinquence::today($previousAttendance->id);
        $attendance = ClubAttendance::with('clubAttendanceLearner')->where('club_register_id', $request->club_register_id)->orderBy('date','desc')->get();
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
        $previousAttendance = ClubAttendance::with('delinquentsPivot','delinquents')->where('club_register_id', $request->club_register_id)->orderBy('date','desc')->first();
        // dd($previousAttendance);
        $club = ClubRegister::with('club.learners.currentEnrollment.section')->findOrFail($request->club_register_id);
        if ($previousAttendance) {
            $delinquents = AttendanceDelinquence::today($previousAttendance->id);
        }
        $club = ClubRegister::with('club.learners.currentEnrollment.section')->findOrFail($request->club_register_id);
        return Inertia::render('ClubAttendanceCreate', [
            'club' => $club,
            'delinquents' => $delinquents ?? [],
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
        $clubMembers = collect($request->members)->mapWithKeys(function ($member, $index) use ($clubAttendance) {
            return [
                $index => [
                    'club_attendance_id' => $clubAttendance->id,
                    'learner_id' => $member['learner_id'],
                    'status' => $member['status'] ?? 'absent',
                    'remarks' => $member['remarks'] ?? null,
                ],
            ];
        })->toArray();
        $clubAttendance->clubAttendanceLearner()->attach($clubMembers);
        $delinquentsMembers = $clubAttendance->clubAttendanceLearner
        ->filter(function ($member) {
            return $member->pivot->status !== 'present';
        })
        ->mapWithKeys(function ($member, $index) use ($clubAttendance) {
            // Explicitly access pivot attributes to ensure they're available
            return [
                $index => [
                    'club_attendance_id' => $clubAttendance->id,
                    'club_attendance_learner_id' => $member->pivot->id, // Ensure pivot->id exists
                    'learner_name' => $member->name, // For Vue display
                    'status' => $member->pivot->status, // Explicitly include pivot status
                    'resolved' => false,
                    'link' => null,
                    'resolved_by' => null,
                    'remarks' => null,
                ],
            ];
        })
        ->values() // Reset keys to numeric indices
        ->toArray();
        $clubAttendance->delinquents()->createMany($delinquentsMembers);
        return redirect()->route('club.attendance', ['club_register_id' => $club->id]);
    }
    public function show(Request $request)
    {
        $attendance = ClubAttendance::with(['clubRegister.club', 'clubAttendanceLearner'])->findOrFail($request->attendance_id);
        $previousAttendance = ClubAttendance::where('club_register_id', $attendance->club_register_id)
            ->where('date', '<', $attendance->date)
            ->orderBy('date', 'desc')
            ->first();
        if ($previousAttendance) {
            $delinquents = AttendanceDelinquence::today($previousAttendance->id);
        }
        return Inertia::render('ClubAttendanceShow', [
            'attendance' => $attendance,
            'delinquents' => $delinquents ?? [],
        ]);
    }
    public function edit(Request $request)
    {
        $attendance = ClubAttendance::with(['clubRegister.club', 'clubAttendanceLearner', 'delinquents'])->findOrFail($request->attendance_id);
        $previousAttendance = ClubAttendance::where('club_register_id', $attendance->club_register_id)
            ->where('date', '<', $attendance->date)
            ->orderBy('date', 'desc')
            ->first();
        if ($previousAttendance) {
            $delinquents = AttendanceDelinquence::today($previousAttendance->id);
        }
        return Inertia::render('ClubAttendanceEdit', [
            'attendance' => $attendance,
            'delinquents' => $delinquents ?? [],
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
        if (auth()->user()->role !== 'admin' && $club->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        $clubAttendanceLearners = [];
        $clubAttendance = ClubAttendance::with('clubAttendanceLearner')->findOrFail($request->attendance['id']);
        foreach ($request->attendance['club_attendance_learner'] as $learner) {
            $clubAttendanceLearners[] = [
                'id' => $learner['pivot']['id'],
                'club_attendance_id' => $learner['pivot']['club_attendance_id'],
                'learner_id' => $learner['pivot']['learner_id'],
                'status' => $learner['pivot']['status'],
                'remarks' => $learner['pivot']['remarks'],
            ];
        }
        $clubAttendance->update($request->attendance);
        $clubAttendance->touch();
        $clubAttendance->clubAttendanceLearner()->detach();
        $clubAttendance->clubAttendanceLearner()->attach($clubAttendanceLearners);
        $clubAttendance->delinquents()->delete();
        $clubAttendance->delinquents()->createMany($request->delinquents);
        return redirect()->back();
    }

    public function resolve(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'action_taken' => 'required',
        ]);
        $delinquent = AttendanceDelinquence::findOrFail($request->id);
        $delinquent->resolved = true;
        $delinquent->actions_taken = $request->action_taken;
        $delinquent->save();
        return redirect()->back();
    }

    public function monthlyAttendance(Request $request)
    {
        $months = ClubAttendance::where('club_register_id', $request->club_id)
            ->selectRaw('DISTINCT DATE_FORMAT(date, "%Y-%m") as month')
            ->orderBy('month', 'asc')
            ->pluck('month');
        $month = $months ? $months->first() : null;
        if($request->month) {
            $month = $request->month;
        }
        $clubAttendance = ClubAttendance::with([
            'clubAttendanceLearners.learner.currentEnrollment.section.gradeLevel',
            'clubAttendanceLearners.delinquent'
        ])
        ->where('club_register_id', $request->club_id)
        ->when($month, function ($query) use ($month) {
            [$year, $month] = explode('-', $month);
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        })
        ->orderBy('date', 'asc')
        ->get();
        return Inertia::render('MonthlyAttendanceReport', [
            'attendance' => $clubAttendance,
            'months' => $months,
            'month' => $month,
        ]);
    }
    public function summary(Request $request)
    {
        $club = ClubRegister::findOrFail($request->club_register_id);
        // if ($club->user_id !== auth()->id()) {
        //     abort(403, 'Unauthorized access.');
        // }
        $summary = Learner::with(['clubAttendance'])
        ->whereHas('clubAttendance', function ($query) use ($request) {
            $query->where('club_register_id', $request->club_register_id);
        })
        ->orderBy('last_name', 'asc')
        ->get();

        return Inertia::render('ClubAttendanceSummary', [
            'club' => $club,
            'attendance' => $summary,
        ]);
    }
}
