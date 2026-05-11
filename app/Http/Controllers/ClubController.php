<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Learner;
use App\Models\ClubOfficer;
use App\Models\ClubManager;
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
            ->with([
                'club',
                'schoolYear',
                'learners.currentEnrollment.section.gradeLevel',
                'clubOfficers.learner.currentEnrollment.section.gradeLevel',
            ])
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
                'nullable',
                'exists:clubs,id',
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

        $clubRegister = ClubRegister::with('club')->findOrFail($request->club_reg_id);
        $resolvedClubId = $clubRegister->club_id ?? $request->club_id;

        abort_unless($resolvedClubId, 422, 'Unable to resolve club id for registration.');

        $learner = Learner::find($request->learner_id);
        $learner->clubRegisters()->attach($request->club_reg_id, [
            'club_id' => $resolvedClubId,
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
                'nullable',
                'exists:clubs,id',
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

        $clubRegister = ClubRegister::with('club')->findOrFail($request->club_reg_id);
        $resolvedClubId = $clubRegister->club_id ?? $request->club_id;

        abort_unless($resolvedClubId, 422, 'Unable to resolve club id for registration.');

        $learner = Learner::find($request->learner_id);
        $learner->clubRegisters()->attach($request->club_reg_id, [
            'club_id' => $resolvedClubId,
            'club_register_id' => $request->club_reg_id,
            'school_year_id' => SchoolYear::current()->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Member registered successfully.');
    }

    public function storeOfficer(Request $request)
    {
        $request->validate([
            'learner_id' => 'required|integer|exists:learners,id',
            'club_reg_id' => 'required|integer|exists:club_registers,id',
            'position' => 'required|string|max:255',
            'order_no' => 'nullable|integer|min:1',
        ]);

        $currentSchoolYear = SchoolYear::current();
        abort_unless($currentSchoolYear, 404);

        $clubManager = ClubManager::where('user_id', auth()->id())
            ->where('club_register_id', $request->club_reg_id)
            ->where('school_year_id', $currentSchoolYear->id)
            ->first();

        abort_unless($clubManager, 403, 'Unauthorized access.');

        $clubRegister = ClubRegister::with('learners')->findOrFail($request->club_reg_id);
        $isClubMember = $clubRegister->learners()->where('learners.id', $request->learner_id)->exists();

        abort_unless($isClubMember, 422, 'The selected learner must be an enlisted club member.');

        $nextOrderNo = (int) ($request->order_no ?? (
            ((int) ClubOfficer::where('club_register_id', $clubRegister->id)->max('order_no')) + 1
        ));

        ClubOfficer::updateOrCreate(
            [
                'club_register_id' => $clubRegister->id,
                'learner_id' => $request->learner_id,
            ],
            [
                'position' => $request->position,
                'order_no' => $nextOrderNo,
                'school_year_id' => $currentSchoolYear->id,
            ]
        );

        return redirect()->back()->with('success', 'Officer assigned successfully.');
    }

    public function destroyOfficer(ClubOfficer $clubOfficer)
    {
        $currentSchoolYear = SchoolYear::current();
        abort_unless($currentSchoolYear, 404);

        $clubManager = ClubManager::where('user_id', auth()->id())
            ->where('club_register_id', $clubOfficer->club_register_id)
            ->where('school_year_id', $currentSchoolYear->id)
            ->first();

        abort_unless($clubManager, 403, 'Unauthorized access.');
        abort_unless((int) $clubOfficer->school_year_id === (int) $currentSchoolYear->id, 403, 'Unauthorized access.');

        $clubOfficer->delete();

        return redirect()->back()->with('success', 'Officer position dissolved successfully.');
    }

    public function unregisterMember(Request $request)
    {
        $request->validate([
            'learner_id' => 'required|integer',
            'club_reg_id' => 'required|integer|exists:club_registers,id',
        ]);
        $learner = Learner::find($request->learner_id);
        $learner->clubRegisters()->detach($request->club_reg_id);
        return redirect()->route('club.members')->with('success', 'Member unregistered successfully.');
    }

    public function clubsList()
    {
        $clubs = ClubRegister::where('school_year_id', SchoolYear::current()->id)->with(['club', 'user', 'schoolYear', 'learners.currentEnrollment.section'])->get();
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
