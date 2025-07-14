<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Learner;
use App\Models\Club;
use App\Models\ClubRegister;
use App\Models\SchoolYear;
use Illuminate\Validation\Rule;

class ClubController extends Controller
{
    public function membersList()
    {
        $learners = Learner::with(['currentClub.currentRegister', 'currentEnrollment.section'])->get();
        return Inertia::render('ClubMembers', [
            'learners' => $learners,
        ]);
    }

    public function registerMember(Request $request)
    {
        // $request->validate([
        //     'learner_id' => 'required',
        //     'club_id' => 'required',
        // ]);
        $request->validate([
            'learner_id' => 'required|integer',
            'club_id' => [
                'required',
                Rule::unique('club_learner')->where(function ($query) use ($request) {
                    return $query->where('learner_id', $request->learner_id)
                                 ->where('school_year_id', SchoolYear::current()->id);
                }),
            ],
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
}
