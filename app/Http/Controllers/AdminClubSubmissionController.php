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
        ->orderBy('created_at', 'desc')
        ->get();
        return Inertia::render('admin/ClubSubmissions', [
            'submissions' => $submissions
        ]);
    }
}
