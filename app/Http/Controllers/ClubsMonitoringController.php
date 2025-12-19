<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;
use App\Models\ClubRegister;
use App\Models\Club;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\Submission;
use App\Models\Ipcr;
use Carbon\Carbon;

class ClubsMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $clubType = $request->club_type ?? 'club';
        $allAdvisers = ClubAttendance::with('clubRegister.user', 'clubRegister.club')
        ->whereHas('clubRegister.club', function ($q) use ($clubType) {
            $q->where('type', $clubType);
        })
        ->get()
        ->pluck('clubRegister.user')
        ->unique('name')
        ->sortBy('name')
        ->pluck('name');

        $attendances = ClubAttendance::with(['clubRegister.club', 'clubRegister.user'])
        ->whereHas('clubRegister.club', function ($q) use ($clubType) {
            $q->where('type', $clubType);
        })
        ->orderBy('date', 'asc')
        ->where('date', '>=', '2025-08-14')
        ->where('date', '<=', '2025-12-05')
        ->get()
        ->groupBy('date')
        ->map(function ($group) use ($allAdvisers) {
            $mergedData = collect([]);

            foreach ($allAdvisers as $adviser) {
                $adviserData = $group->firstWhere('clubRegister.user.name', $adviser);

                if ($adviserData) {
                    $mergedData->push([
                        'adviser' => $adviser,
                        'submitted_on' => Carbon::parse($adviserData->created_at)->format('Y-m-d'),
                        'edited_on' => Carbon::parse($adviserData->updated_at)->format('Y-m-d'),
                        'club' => $adviserData->clubRegister->club->name,
                        'q' => $adviserData->date->isSameDay($adviserData->updated_at) ? 5 : 4,
                        'e' => 5,
                        't' => $adviserData->date->isSameDay($adviserData->created_at) ? 5 : 1,
                    ]);
                } else {
                    $mergedData->push([
                        'adviser' => $adviser,
                        'submitted_on' => null,
                        'edited_on' => null,
                        'club' => null,
                        'q' => 1,
                        'e' => 2,
                        't' => 1,
                    ]);
                }
            }
            return $mergedData;
        });
        $accomplishment_reports = [];
        $accomplishment_reports2 = [];
        $monthly_attendance_reports = [];
        $monthly_attendance_reports2 = [];
        $ipcr = Ipcr::where('school_year_id',SchoolYear::current()->id)
                ->where('semester', 1)
                ->where('club_type', $clubType)
                ->first();
        if($request->target_type && $request->target_type === '5') {
             $monthly_attendance_reports = Submission::with(['user'])->where(['name' => 'monthly_attendance_report', 'status' => 'completed'])->get();
             $monthly_attendance_reports2 = Submission::with(['user'])->where(['name' => 'monthly_attendance_report_2nd_quarter', 'status' => 'completed'])->get();
        }
        if($request->target_type && $request->target_type === '10') {
            $accomplishment_reports = Submission::with(['user'])->where(['name' => 'accomplishment_report', 'status' => 'completed'])->get();
            $accomplishment_reports2 = Submission::with(['user'])->where(['name' => 'accomplishment_report_2nd_quarter', 'status' => 'completed'])->get();
        }
        if($request->target_type && $request->target_type === '11') {
            $accomplishment_reports = Submission::with(['user'])->where(['name' => 'attendance_summary_report_1st_semester', 'status' => 'completed'])->get();
        }
        // $submission = Submission::where(['club_register_id' => $id, 'name' => 'monthly_attendance_report', 'status' => 'completed'])->first();
        return Inertia::render('admin/ClubsMonitoring', [
            'advisers' => $allAdvisers,
            'attendances' => $attendances,
            'accomplishment_reports' => $accomplishment_reports,
            'accomplishment_reports2' => $accomplishment_reports2,
            'monthly_attendance_reports' => $monthly_attendance_reports,
            'monthly_attendance_reports2' => $monthly_attendance_reports2,
            'ipcr' => $ipcr
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'semester' => 'required',
            'school_year_id' => 'required',
            'monitoring' => 'required',
            'club_type' => 'required'
        ]);
        Ipcr::create([
            'semester' => $request->semester,
            'school_year_id' => $request->school_year_id,
            'monitoring' => $request->monitoring,
            'club_type' => $request->club_type
        ]);
        return redirect()->route('admin.clubs.monitoring');
    }

    public function update(Request $request)
    {
        $request->validate([
            'semester' => 'required',
            'school_year_id' => 'required',
            'monitoring' => 'required',
            'club_type' => 'required'
        ]);
        Ipcr::where('id', $request->id)->update([
            'semester' => $request->semester,
            'school_year_id' => $request->school_year_id,
            'monitoring' => $request->monitoring,
            'club_type' => $request->club_type
        ]);
        return redirect()->route('admin.clubs.monitoring');
    }
    public function show($id)
    {
        $club = ClubRegister::with('club', 'user')->findOrFail($id);
        $clubType = $club->club->type;
        $adviser = ClubAttendance::with('clubRegister.user', 'clubRegister.club')
        ->where('club_register_id', $id)
        ->whereHas('clubRegister.club', function ($q) use ($clubType) {
            $q->where('type', $clubType);
        })->get()
        ->pluck('clubRegister.user')
        ->unique('name')
        ->sortBy('name')
        ->pluck('name');
        $attendances = ClubAttendance::with(['clubRegister.club', 'clubRegister.user'])
        ->whereHas('clubRegister.club', function ($q) use ($clubType) {
            $q->where('type', $clubType);
        })
        ->orderBy('date', 'asc')
        ->where('date', '>=', '2025-08-14')
        ->where('date', '<=', '2025-12-05')
        ->get()
        ->groupBy('date')
        ->map(function ($group) use ($adviser) {
            $mergedData = collect([]);

            foreach ($adviser as $adviser) {
                $adviserData = $group->firstWhere('clubRegister.user.name', $adviser);

                if ($adviserData) {
                    $mergedData->push([
                        'adviser' => $adviser,
                        'club' => $adviserData->clubRegister->club->name,
                        'submitted_on' => Carbon::parse($adviserData->created_at)->format('Y-m-d'),
                        'edited_on' => Carbon::parse($adviserData->updated_at)->format('Y-m-d'),
                        'q' => $adviserData->date->isSameDay($adviserData->updated_at) ? 5 : 4,
                        'e' => 5,
                        't' => $adviserData->date->isSameDay($adviserData->created_at) ? 5 : 1,
                    ]);
                } else {
                    $mergedData->push([
                        'adviser' => $adviser,
                        'submitted_on' => null,
                        'edited_on' => null,
                        'club' => null,
                        'q' => 1,
                        'e' => 2,
                        't' => 1,
                    ]);
                }
            }
            return $mergedData;
        });
        $submission = Submission::where(['club_register_id' => $id, 'name' => 'monthly_attendance_report', 'status' => 'completed'])->first();
        $accomplishment = Submission::where(['club_register_id' => $id, 'name' => 'accomplishment_report', 'status' => 'completed'])->first();
        $submission2 = Submission::where(['club_register_id' => $id, 'name' => 'monthly_attendance_report_2nd_quarter', 'status' => 'completed'])->first();
        $accomplishment2 = Submission::where(['club_register_id' => $id, 'name' => 'accomplishment_report_2nd_quarter', 'status' => 'completed'])->first();
        $target11 = Submission::where(['club_register_id' => $id, 'name' => 'attendance_summary_report_1st_semester', 'status' => 'completed'])->first();
        return Inertia::render('ClubMonitoring', [
            'club' => $club,
            'advisers' => $adviser,
            'attendances' => $attendances,
            'submission' => $submission,
            'accomplishment' => $accomplishment,
            'submission2' => $submission2,
            'accomplishment2' => $accomplishment2,
            'target11' => $target11,
        ]);
    }
}
