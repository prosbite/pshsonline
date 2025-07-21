<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubRegister;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use App\Models\Enrollment;
use App\Models\Club;
class AdminClubController extends Controller
{
    public function index()
    {
        $registered_clubs = ClubRegister::query()
            ->with(['club', 'user', 'schoolYear']) // Eager load the main relationships
            ->select(
                'club_registers.*', // Select all columns from club_registers
                // Subquery to count total learners for each club via the pivot table
                DB::raw('(SELECT COUNT(cl.learner_id) FROM club_learner cl JOIN learners l ON cl.learner_id = l.id WHERE cl.club_id = club_registers.club_id) as total_members'),
                // Subquery to count female learners for each club via the pivot table
                DB::raw('(SELECT COUNT(cl.learner_id) FROM club_learner cl JOIN learners l ON cl.learner_id = l.id WHERE cl.club_id = club_registers.club_id AND l.gender = "Female") as female_members'),
                // Subquery to count male learners for each club via the pivot table
                DB::raw('(SELECT COUNT(cl.learner_id) FROM club_learner cl JOIN learners l ON cl.learner_id = l.id WHERE cl.club_id = club_registers.club_id AND l.gender = "Male") as male_members')
            )
            ->where('club_registers.school_year_id', SchoolYear::current()->id)
            // Join the clubs table to facilitate ordering by club name
            ->join('clubs', 'club_registers.club_id', '=', 'clubs.id')
            ->orderBy('clubs.name', 'asc') // Order by the club's name
            ->get();

        $unlisted_learners = Club::unlistedMembers();

        $club_student_count = Enrollment::whereHas('section.gradeLevel', function ($query) {
                    $query->whereNotIn('grade_level', [11, 12]);
                })
                ->count();

        return Inertia::render('admin/Clubs', [
            'registered_clubs' => $registered_clubs,
            'club_student_count' => $club_student_count,
            'unlisted_learners' => $unlisted_learners
        ]);
    }

    public function show(ClubRegister $club)
    {
        $club = $club;
        $registered_clubs = ClubRegister::with(['club'])->where('school_year_id', SchoolYear::current()->id)->get();
        return Inertia::render('admin/ClubDetails', [
            'club' => $club->load(['club.learners.currentEnrollment.section.gradeLevel', 'user', 'schoolYear']),
            'registered_clubs' => $registered_clubs,
            'current_club' => $club->id
        ]);
    }

    public function unregisterMember(Request $request)
    {
        $request->validate([
            'learner_id' => 'required|integer',
            'club_id' => 'required|integer',
        ]);
        $learner = Learner::find($request->learner_id);
        $learner->clubs()->detach($request->club_id);
        return redirect()->route('admin.club.show', $request->club_reg_id)->with('success', 'Member unregistered successfully.');
    }
}
