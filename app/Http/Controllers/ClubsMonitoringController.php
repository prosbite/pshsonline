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
        $clubs = ClubRegister::with('club', 'user')->get();
        $allAdvisers = ClubAttendance::with('clubRegister.user', 'clubRegister.club')
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
                ->where('semester', $request->semester ?? 1)
                ->where('club_type', $clubType)
                ->first();

        $monthly_attendance_reports = Submission::with(['user'])->where(['name' => 'monthly_attendance_report', 'status' => 'completed'])->get();
        $monthly_attendance_reports2 = Submission::with(['user'])->where(['name' => 'monthly_attendance_report_2nd_quarter', 'status' => 'completed'])->get();
        $accomplishment_reports = Submission::with(['user'])->where(['name' => 'accomplishment_report', 'status' => 'completed'])->get();
        $accomplishment_reports2 = Submission::with(['user'])->where(['name' => 'accomplishment_report_2nd_quarter', 'status' => 'completed'])->get();
        $attendance_summary_report_1st_semester = Submission::with(['user'])->where(['name' => 'attendance_summary_report_1st_semester', 'status' => 'completed'])->get();
        // dd($monthly_attendance_reports);
        // $submission = Submission::where(['club_register_id' => $id, 'name' => 'monthly_attendance_report', 'status' => 'completed'])->first();
        // dd($attendances);
        return Inertia::render('admin/ClubsMonitoring', [
            'advisers' => $allAdvisers,
            'attendances' => $attendances,
            'accomplishment_reports' => $accomplishment_reports,
            'accomplishment_reports2' => $accomplishment_reports2,
            'monthly_attendance_reports' => $monthly_attendance_reports,
            'monthly_attendance_reports2' => $monthly_attendance_reports2,
            'ipcr' => $ipcr,
            'clubs' => $clubs,
            'semester' => $request->semester ?? 2,
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
    public function show(Request $request, $id)
    {
        $club = ClubRegister::with('club', 'user')->findOrFail($id);
        $clubType = $club->club->type;
        $startDate = $request->semester === 's1' ? '2025-08-14' : '2026-01-14';
        $endDate = $request->semester === 's1' ? '2025-12-31' : '2026-04-24';
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
        ->where('date', '>=', $startDate)
        ->where('date', '<=', $endDate)
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
        $monthly_attendance_report1 = $request->semester === 's1' ? 'monthly_attendance_report' : 'monthly_attendance_report_3rd_quarter';
        $monthly_attendance_report2 = $request->semester === 's1' ? 'monthly_attendance_report_2nd_quarter' : 'monthly_attendance_report_4th_quarter';
        $accomplishment_report1 = $request->semester === 's1' ? 'accomplishment_report' : 'accomplishment_report_3rd_quarter';
        $accomplishment_report2 = $request->semester === 's1' ? 'accomplishment_report_2nd_quarter' : 'accomplishment_report_4th_quarter';
        $submission = Submission::where(['club_register_id' => $id, 'name' => $monthly_attendance_report1, 'status' => 'completed'])->first();
        $accomplishment = Submission::where(['club_register_id' => $id, 'name' => $accomplishment_report1, 'status' => 'completed'])->first();
        $submission2 = Submission::where(['club_register_id' => $id, 'name' => $monthly_attendance_report2, 'status' => 'completed'])->first();
        $accomplishment2 = Submission::where(['club_register_id' => $id, 'name' => $accomplishment_report2, 'status' => 'completed'])->first();
        $major_activity_proposal = Submission::where(['club_register_id' => $id, 'name' => 'activity_proposal_major_activity', 'status' => 'completed'])->first();
        $major_activity_report = Submission::where(['club_register_id' => $id, 'name' => 'activity_report_major_activity', 'status' => 'completed'])->first();
        $target11 = Submission::where(['club_register_id' => $id, 'name' => 'attendance_summary_report_1st_semester', 'status' => 'completed'])->first();
        $ipcr = Ipcr::where('school_year_id',SchoolYear::current()->id)
                ->where('semester', $request->semester === 's1' ? 1 : 2)
                ->where('club_type', $clubType)
                ->first();
        return Inertia::render('ClubMonitoring', [
            'club' => $club,
            'advisers' => $adviser,
            'attendances' => $attendances,
            'submission' => $submission,
            'accomplishment' => $accomplishment,
            'submission2' => $submission2,
            'accomplishment2' => $accomplishment2,
            'target11' => $target11,
            'ipcr' => $ipcr,
            'semester' => $request->semester ?? 's2',
            'major_activity_proposal' => $major_activity_proposal,
            'major_activity_report' => $major_activity_report
        ]);
    }
}
