<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function setCurrent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_year_id' => ['required', 'integer', 'exists:school_years,id'],
        ]);

        $schoolYear = SchoolYear::findOrFail($validated['school_year_id']);
        SchoolYear::syncSession($schoolYear);

        return redirect()->back()->with('success', 'School year updated.');
    }
}
