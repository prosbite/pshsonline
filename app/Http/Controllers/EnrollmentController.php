<?php

namespace App\Http\Controllers;

use App\Models\GradeLevel;
use App\Models\Learner;
use App\Models\Section;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Enrollment', [
            'gradeLevels' => GradeLevel::with('sections')->get(),
            'learners' => Enrollment::with(['learner', 'section.gradeLevel'])->get(),
            'sections' => Section::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'gender' => 'required',
            'grade_level_id' => 'required',
            'section_id' => 'required',
        ]);
        $learner = Learner::create($request->all());
        $learner->enrollments()->create([
            'section_id' => $request->section_id,
            'school_year_id' => SchoolYear::current()->id,
        ]);

        return redirect()->route('enrollment')->with([
            'success' => 'Student enrolled successfully.',
            'learners' => Learner::with(['currentEnrollment.section'])->get(),
        ]);
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'learners' => 'required',
            'learners.*.first_name' => ['required', 'string'],
            'learners.*.last_name' => ['required', 'string'],
            'learners.*.gender' => ['required', 'string'],
            'learners.*.grade_level_id' => ['required', 'integer'],
            'learners.*.section_id' => ['required', 'integer'],
        ]);
        foreach ($validated['learners'] as $index => $learner) {
            $exists = DB::table('learners')
                ->where('first_name', $learner['first_name'])
                ->where('last_name', $learner['last_name'])
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    "learners.{$index}" => "The name {$learner['first_name']} {$learner['last_name']} already exists in the system.",
                ])->withInput();
            }
        }
        foreach ($validated['learners'] as $vlearner) {
            $learner = Learner::create($vlearner);
            $learner->enrollments()->create([
                'section_id' => $vlearner['section_id'],
                'school_year_id' => SchoolYear::current()->id,
            ]);
        }

        return redirect()->route('enrollment')->with([
            'success' => 'Students enrolled successfully.',
            'learners' => Learner::with(['currentEnrollment.section'])->get(),
        ]);
    }
}
