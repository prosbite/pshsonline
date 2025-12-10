<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;
use App\Models\ClubRegister;
use App\Models\Club;
use App\Models\User;
use App\Models\Submission;
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
        $monthly_attendance_reports = Submission::with(['user'])->where(['name' => 'monthly_attendance_report', 'status' => 'completed'])->get();
        $accomplishment_reports = [];
        if($request->target_type && $request->target_type === '10') {
            $accomplishment_reports = Submission::with(['user'])->where(['name' => 'accomplishment_report', 'status' => 'completed'])->get();
        }
        if($request->target_type && $request->target_type === '11') {
            $accomplishment_reports = Submission::with(['user'])->where(['name' => 'attendance_summary_report_1st_semester', 'status' => 'completed'])->get();
        }
        // $submission = Submission::where(['club_register_id' => $id, 'name' => 'monthly_attendance_report', 'status' => 'completed'])->first();
        return Inertia::render('admin/ClubsMonitoring', [
            'advisers' => $allAdvisers,
            'attendances' => $attendances,
            'accomplishment_reports' => $accomplishment_reports,
            'monthly_attendance_reports' => $monthly_attendance_reports,
        ]);
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
        return Inertia::render('ClubMonitoring', [
            'club' => $club,
            'advisers' => $adviser,
            'attendances' => $attendances,
            'submission' => $submission,
            'accomplishment' => $accomplishment,
        ]);
    }
}
