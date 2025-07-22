<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Assessment;
use Inertia\Inertia;
class AdminAssessmentController extends Controller
{
    public function mixmatch()
    {
        // $learners = Enrollment::join('learners', 'learners.id', '=', 'enrollments.learner_id')
        // ->select(
        //     'enrollments.id as enrollment_id',
        //     'learners.id as learner_id',
        //     'learners.first_name',
        //     'learners.last_name',
        //     'learners.gender',
        //     'learners.middle_name',
        // )
        // ->get();
        $assessments = Assessment::where(['enrollment_id' => null, 'entryCode' => 'diagnostic-2025'])->get();
        $assessments->each(function ($assessment) use ($learners) {
            $assessment->match = null;
        });
        // $learnerMap = $learners->keyBy(fn($l) => strtolower(trim($l->first_name . ' ' . $l->last_name)));
        $matched = [];
        // foreach ($assessments as $assessment) {
        //     $fullName = strtolower(trim($assessment->firstName . ' ' . $assessment->lastName));

        //     if ($learner = $learnerMap->get($fullName)) {
        //         $assessment->enrollment_id = $learner->enrollment_id;
        //         $matched[] = $assessment;
        //         $assessment->save(); // or batch store later
        //     }
        // }
        // dd($assessments);
        return Inertia::render('admin/AssessmentMixmatch', [
            'assessments' => $assessments,
        ]);
    }
    public function matchAssessment(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required',
            'enrollment_id' => 'required',
        ]);
        $assessment = Assessment::find($request->assessment_id);
        $assessment->enrollment_id = $request->enrollment_id;
        $assessment->save();
        return Inertia::location(route('admin.assessment.mixmatch'));
    }
}
