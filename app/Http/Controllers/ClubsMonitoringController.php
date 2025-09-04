<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;
use App\Models\ClubRegister;
use App\Models\Club;
use App\Models\User;

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
        ->where('date', '>=', '2025-08-08')
        ->get()
        ->groupBy('date')
        ->map(function ($group) use ($allAdvisers) {
            $mergedData = collect([]);

            foreach ($allAdvisers as $adviser) {
                $adviserData = $group->firstWhere('clubRegister.user.name', $adviser);

                if ($adviserData) {
                    $mergedData->push([
                        'adviser' => $adviser,
                        'club' => $adviserData->clubRegister->club->name,
                        'q' => 5,
                        'e' => $adviserData->date->isSameDay($adviserData->updated_at) ? 5 : 4,
                        't' => $adviserData->date->isSameDay($adviserData->created_at) ? 5 : 1,
                    ]);
                } else {
                    $mergedData->push([
                        'adviser' => $adviser,
                        'club' => null,
                        'q' => 0,
                        'e' => 0,
                        't' => 0,
                    ]);
                }
            }
            return $mergedData;
        });
        return Inertia::render('admin/ClubsMonitoring', [
            'advisers' => $allAdvisers,
            'attendances' => $attendances,
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
        ->where('date', '>=', '2025-08-08')
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
                        'q' => 5,
                        'e' => $adviserData->date->isSameDay($adviserData->updated_at) ? 5 : 4,
                        't' => $adviserData->date->isSameDay($adviserData->created_at) ? 5 : 1,
                    ]);
                } else {
                    $mergedData->push([
                        'adviser' => $adviser,
                        'club' => null,
                        'q' => 0,
                        'e' => 0,
                        't' => 0,
                    ]);
                }
            }
            return $mergedData;
        });
        return Inertia::render('ClubMonitoring', [
            'club' => $club,
            'advisers' => $adviser,
            'attendances' => $attendances,
        ]);
    }
}
