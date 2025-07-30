<?php

namespace App\Http\Controllers;

use App\Models\ClubRegister;
use Illuminate\Http\Request;
use App\Models\Submission;
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
        Submission::create($request->all());
        return redirect()->route('club.submissions');
    }
}
