<?php

namespace App\Http\Controllers;

use App\Models\AdviserAttendance;
use Illuminate\Http\Request;
use App\Models\ClubRegister;
use App\Models\SchoolYear;
use Inertia\Inertia;

class AdviserAttendanceController extends Controller
{
    public function index()
    {
        $attendances = AdviserAttendance::with('users.clubRegisters.club')->where('school_year_id', SchoolYear::current()->id)->get();
        return Inertia::render('admin/AdvisersAttendanceList', compact('attendances'));
    }
    public function create()
    {
        $clubAdvisers = ClubRegister::with('user', 'club')->where('school_year_id', SchoolYear::current()->id)->get();
        return Inertia::render('admin/AdvisersAttendanceCreate', compact('clubAdvisers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'activity' => 'required',
            'members' => 'required|array',
        ]);
        $request['school_year_id'] = SchoolYear::current()->id;
        $adviserAttendance = AdviserAttendance::create($request->all());
        $adviserMembers = collect($request->members)->mapWithKeys(function ($member, $index) use ($adviserAttendance) {
            return [
                $index => [
                    'adviser_attendance_id' => $adviserAttendance->id,
                    'user_id' => $member['user_id'],
                    'status' => $member['status'],
                    'remarks' => $member['remarks'],
                ],
            ];
        });
        $adviserAttendance->users()->attach($adviserMembers);
        return redirect()->route('admin.advisers.attendance')->with('success', 'Adviser attendance created successfully.');
    }

    public function show($id)
    {
        $attendance = AdviserAttendance::with('users.clubRegisters.club')->where('school_year_id', SchoolYear::current()->id)->find($id);
        return Inertia::render('admin/AdvisersAttendanceShow', compact('attendance'));
    }
}
