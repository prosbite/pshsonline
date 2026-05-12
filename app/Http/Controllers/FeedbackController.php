<?php

namespace App\Http\Controllers;

use App\Models\ClubRegister;
use App\Models\Feedback;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with([
            'clubRegister.club',
            'user',
            'schoolYear',
        ])->latest()->get();

        return Inertia::render('admin/FeedbackResults', [
            'feedbacks' => $feedbacks,
        ]);
    }

    public function create(Request $request)
    {
        $schoolYear = SchoolYear::current();
        abort_unless($schoolYear, 404);

        $club = ClubRegister::with('club', 'user', 'schoolYear')
            ->where('school_year_id', $schoolYear->id)
            ->where('user_id', auth()->id())
            ->findOrFail($request->club_id);

        $feedbackExists = Feedback::where('club_register_id', $club->id)
            ->where('user_id', auth()->id())
            ->where('school_year_id', $schoolYear->id)
            ->exists();

        if ($feedbackExists) {
            return redirect()->route('club.certificates', ['club_id' => $club->id]);
        }

        return Inertia::render('ClubFeedback', [
            'club' => $club,
            'schoolYear' => $schoolYear,
        ]);
    }

    public function store(Request $request)
    {
        $schoolYear = SchoolYear::current();
        abort_unless($schoolYear, 404);

        $club = ClubRegister::where('school_year_id', $schoolYear->id)
            ->where('user_id', auth()->id())
            ->findOrFail($request->club_id);

        $validated = $request->validate([
            'feedback.photo_attachment_used' => ['required', Rule::in(['yes', 'no'])],
            'feedback.photo_attachment_rating' => ['required', Rule::in([
                'very_helpful_and_works_properly',
                'helpful_but_needs_minor_improvement',
                'not_helpful_or_has_issues',
                'not_applicable_have_not_used',
            ])],
            'feedback.documentation_used' => ['required', Rule::in(['yes', 'no'])],
            'feedback.documentation_rating' => ['required', Rule::in([
                'very_helpful_and_works_properly',
                'helpful_but_needs_minor_improvement',
                'not_helpful_or_has_issues',
                'not_applicable_have_not_used',
            ])],
            'feedback.overall_system_improvement' => ['required', Rule::in([
                'very_effective',
                'effective',
                'slightly_effective',
                'not_effective',
            ])],
            'feedback.recommend_continued_use' => ['required', Rule::in([
                'yes',
                'no',
                'not_sure',
            ])],
            'feedback.recommendation_explanation' => ['required', 'string', 'max:5000'],
        ]);

        Feedback::create([
            'club_register_id' => $club->id,
            'user_id' => auth()->id(),
            'school_year_id' => $schoolYear->id,
            'feedback' => $validated['feedback'],
        ]);

        return redirect()->route('club.certificates', ['club_id' => $club->id]);
    }
}
