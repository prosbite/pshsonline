<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Learner;
use App\Models\ClubRegister;
use App\Models\Section;
use App\Models\User;
use App\Models\SchoolYear;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ClubController extends Controller
{
    public function membersList()
    {
        $my_clubs = ClubRegister::where('school_year_id', SchoolYear::current()->id)
            ->where('user_id', auth()->user()->id)
            ->with(['club', 'schoolYear', 'learners.currentEnrollment.section.gradeLevel'])
            ->get();
        return Inertia::render('ClubMembers', [
            'clubs' => $my_clubs,
        ]);
    }

    public function registerMember(Request $request)
    {
        $request->validate([
            'learner_id' => 'required|integer',
            'club_reg_id' => 'required|exists:club_registers,id',
            'club_id' => [
                'required',
                Rule::unique('club_learner')->where(function ($query) use ($request) {
                    return $query->where('learner_id', $request->learner_id)
                                 ->where('school_year_id', SchoolYear::current()->id);
                }),
            ],
        ]);

        $currentMembersCount = DB::table('club_learner')
            ->where(function ($query) use ($request) {
                $query->where('club_register_id', $request->club_reg_id)
                    ->orWhere(function ($legacyQuery) use ($request) {
                        $legacyQuery->whereNull('club_register_id')
                            ->where('club_id', $request->club_id)
                            ->where('school_year_id', SchoolYear::current()->id);
                    });
            })
            ->count();

        if ($currentMembersCount >= 33) {
            return redirect()->back()->with('error', 'This club has reached the maximum of 26 members.');
        }

        $learner = Learner::find($request->learner_id);
        $learner->clubs()->attach($request->club_id, [
            'club_register_id' => $request->club_reg_id,
            'school_year_id' => SchoolYear::current()->id,
        ]);
        return redirect()->route('admin.club.show', $request->club_reg_id)->with('success', 'Member registered successfully.');
    }

    public function registerManagerMember(Request $request)
    {
        $request->validate([
            'learner_id' => 'required|integer',
            'club_reg_id' => 'required|exists:club_registers,id',
            'club_id' => [
                'required',
                Rule::unique('club_learner')->where(function ($query) use ($request) {
                    return $query->where('learner_id', $request->learner_id)
                                 ->where('school_year_id', SchoolYear::current()->id);
                }),
            ],
        ]);

        $currentMembersCount = DB::table('club_learner')
            ->where(function ($query) use ($request) {
                $query->where('club_register_id', $request->club_reg_id)
                    ->orWhere(function ($legacyQuery) use ($request) {
                        $legacyQuery->whereNull('club_register_id')
                            ->where('club_id', $request->club_id)
                            ->where('school_year_id', SchoolYear::current()->id);
                    });
            })
            ->count();

        if ($currentMembersCount >= 26) {
            return redirect()->back()->with('error', 'This club has reached the maximum of 26 members.');
        }

        $learner = Learner::find($request->learner_id);
        $learner->clubs()->attach($request->club_id, [
            'club_register_id' => $request->club_reg_id,
            'school_year_id' => SchoolYear::current()->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Member registered successfully.');
    }

    public function unregisterMember(Request $request)
    {
        $request->validate([
            'learner_id' => 'required|integer',
            'club_id' => 'required|integer',
        ]);
        $learner = Learner::find($request->learner_id);
        $learner->clubs()->detach($request->club_id);
        return redirect()->route('club.members')->with('success', 'Member unregistered successfully.');
    }

    public function clubsList()
    {
        $clubs = ClubRegister::where('school_year_id', SchoolYear::current()->id)->with(['club', 'user', 'schoolYear', 'club.learners.currentEnrollment.section'])->get();
        return Inertia::render('Clubs', [
            'clubs' => $clubs,
            'entrants' => Learner::clubEntrants(),
            'users' => User::all(),
            'sections' => Section::all(),
        ]);
    }

    public function updateClub(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string',
            'user_id' => 'required|integer',
            'description' => 'string',
        ]);
        $club = ClubRegister::find($request->id);
        $club->update([
            'user_id' => $request->user_id,
        ]);
        $club->club()->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return redirect()->route('admin.club.list')->with('success', 'Club updated successfully.');
    }
}
