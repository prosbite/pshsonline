<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

class StudentController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->search;
        $learners = Enrollment::with(['learner.currentClub','section.gradeLevel'])->whereHas('learner', function ($query) use ($search) {
            $query->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%");
        })->get();

        return response()->json($learners);
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
        ]);
        $learner = Enrollment::find($request->id);
        $learner->learner->update($request->all());
        return redirect()->route('enrollment')->with('success', 'Member updated successfully.');
    }
}
