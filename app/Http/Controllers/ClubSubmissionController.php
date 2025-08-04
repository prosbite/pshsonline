<?php

namespace App\Http\Controllers;

use App\Models\ClubRegister;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\SubmissionTracker;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ClubSubmissionController extends Controller
{
    public function index()
    {
        $clubRegister = ClubRegister::with('club')->findOrFail(Auth::user()->clubRegisters->first()->id);
        $submissions = Submission::getSubmissionsForClubRegister($clubRegister->id);
        return Inertia::render('ClubSubmissions', compact('submissions','clubRegister'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'club_register_id' => 'required',
            'url' => 'nullable',
            'user_id' => 'required',
        ]);
        $submission = Submission::create($request->all());
        SubmissionTracker::create([
            'submission_id' => $submission->id,
            'status' => 'pending',
            'remarks' => $request->remarks,
            'updated_by' => Auth::user()->id,
        ]);
        return redirect()->route('club.submissions');
    }

    public function update(Submission $submission, Request $request)
    {
        $request->validate([
            'status' => 'required',
            'remarks' => 'nullable',
            'url' => 'nullable',
        ]);
        $submission->update([
            'status' => request('status'),
            'remarks' => request('remarks'),
            'url' => request('url'),
        ]);
        SubmissionTracker::create([
            'submission_id' => $submission->id,
            'status' => request('status'),
            'updated_by' => Auth::user()->id,
        ]);
        return redirect()->back();
    }
}
