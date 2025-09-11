<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AccomplishmentReport;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\ClubAttendance;

class ClubAccomplishmentController extends Controller
{
    public function index()
    {
        $quarter = Quarter::current();
        $schoolYear = SchoolYear::current();
        $clubRegisterId = auth()->user()->clubRegisters[0]?->id;
        $activities = ClubAttendance::where('club_register_id', $clubRegisterId)->where('school_year_id', $schoolYear->id)->get();
        $report = AccomplishmentReport::where('club_register_id', $clubRegisterId)->where('quarter_id', $quarter->id)->where('school_year_id', $schoolYear->id)->first();
        return Inertia::render('ClubAccomplishment', [
            'activities' => $activities,
            'quarter' => $quarter,
            'schoolYear' => $schoolYear,
            'report' => $report,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'report' => 'required',
            'quarter_id' => 'required',
            'school_year_id' => 'required',
            'club_register_id' => 'required',
        ]);
        if($request->id) {
            AccomplishmentReport::find($request->id)->update([
                'report' => json_encode($request->report),
            ]);
        } else {
        AccomplishmentReport::create([
            'report' => json_encode($request->report),
            'quarter_id' => $request->quarter_id,
            'school_year_id' => $request->school_year_id,
            'club_register_id' => $request->club_register_id,
        ]);
            return Inertia::location(route('club.accomplishment.quarterly'));
        }
    }
}
