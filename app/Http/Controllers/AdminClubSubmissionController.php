<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Submission;
use App\Models\SchoolYear;
use App\Models\SubmissionTracker;
use Illuminate\Support\Facades\Auth;
class AdminClubSubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::with('clubRegister.club', 'clubRegister.user', 'clubRegister.schoolYear', 'submissionTrackers.updatedBy')
        ->whereHas('clubRegister', function ($q) {
            $q->where('school_year_id', SchoolYear::current()->id);
        })
        ->orderBy('created_at', 'desc')
        ->get();
        return Inertia::render('admin/ClubSubmissions', [
            'submissions' => $submissions
        ]);
    }

    public function update(Submission $submission)
    {
        $submission->update([
            'status' => request('status')
        ]);
        SubmissionTracker::create([
            'submission_id' => $submission->id,
            'status' => request('status'),
            'updated_by' => Auth::user()->id,
        ]);
        return redirect()->back();
    }
}
