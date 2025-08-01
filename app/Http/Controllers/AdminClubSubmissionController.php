<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Submission;
use App\Models\SchoolYear;

class AdminClubSubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::with('clubRegister.club', 'clubRegister.user', 'clubRegister.schoolYear')
        ->whereHas('clubRegister', function ($q) {
            $q->where('school_year_id', SchoolYear::current()->id);
        })
        ->orderBy('created_at', 'asc')
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
        return redirect()->back();
    }
}
