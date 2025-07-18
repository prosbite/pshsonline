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

class ClubController extends Controller
{
    public function membersList()
    {
        $my_clubs = ClubRegister::where('school_year_id', SchoolYear::current()->id)->where('user_id', auth()->user()->id)->with(['club', 'schoolYear', 'club.learners.currentEnrollment.section'])->get();
        return Inertia::render('ClubMembers', [
            'clubs' => $my_clubs,
        ]);
    }

    public function registerMember(Request $request)
    {
        $request->validate([
            'learner_id' => 'required|integer',
            'club_id' => [
                'required',
                Rule::unique('club_learner')->where(function ($query) use ($request) {
                    return $query->where('learner_id', $request->learner_id)
                                 ->where('school_year_id', SchoolYear::current()->id);
                }),
            ],
            'grade_level' => ['required', 'integer', 'lte:10'],
        ]);
        $learner = Learner::find($request->learner_id);
        $learner->clubs()->attach($request->club_id, [
            'school_year_id' => SchoolYear::current()->id,
        ]);
        return redirect()->route('club.members')->with('success', 'Member registered successfully.');
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
        return redirect()->route('club.list')->with('success', 'Club updated successfully.');
    }
}
