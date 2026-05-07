<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubRegister;
use App\Models\Enrollment;
use App\Models\Learner;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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
        // dd($registered_clubs);
        $unlisted_learners = Club::unlistedMembers();
        // dd($unlisted_learners);
        $grade_levels = [7, 8, 9, 10];

        $grade_level_breakdown = Enrollment::with('section.gradeLevel')
            ->whereHas('section.gradeLevel', function ($query) use ($grade_levels) {
                $query->whereIn('grade_level', $grade_levels);
            })
            ->where('school_year_id', SchoolYear::current()->id)
            ->get()
            ->groupBy(function ($enrollment) {
                return $enrollment->section?->gradeLevel?->grade_level;
            })
            ->map(function ($items, $grade_level) {
                return [
                    'grade_level' => (int) $grade_level,
                    'count' => $items->count(),
                ];
            })
            ->values();

        $total_students_g7_g10 = $grade_level_breakdown->sum('count');

        return Inertia::render('admin/Clubs', [
            'registered_clubs' => $registered_clubs,
            'club_student_count' => $total_students_g7_g10,
            'total_students_g7_g10' => $total_students_g7_g10,
            'grade_level_breakdown' => $grade_level_breakdown,
            'unlisted_learners' => $unlisted_learners,
        ]);
    }

    public function show(ClubRegister $club)
    {
        $club = $club;
        $registered_clubs = ClubRegister::with(['club'])->where('school_year_id', SchoolYear::current()->id)->get();

        return Inertia::render('admin/ClubDetails', [
            'club' => $club->load(['club.learners.currentEnrollment.section.gradeLevel', 'user', 'schoolYear', 'externalinks']),
            'registered_clubs' => $registered_clubs,
            'current_club' => $club->id,
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

    public function registerPage()
    {
        // Get all clubs
        $clubs = Club::orderBy('name', 'asc')->get();
        $currentSchoolYearId = SchoolYear::current()->id;

        // Get registered clubs for current school year
        $registeredClubIds = ClubRegister::where('school_year_id', $currentSchoolYearId)
            ->pluck('club_id')
            ->toArray();

        // Start from all advisers, then exclude anyone already assigned to a club this school year.
        $assignedUserIds = ClubRegister::where('school_year_id', $currentSchoolYearId)
            ->pluck('user_id')
            ->toArray();

        $usersWithoutClubs = User::query()
            ->where('role', 'club adviser')
            ->whereNotIn('id', $assignedUserIds)
            ->orderBy('name')
            ->get();

        return Inertia::render('admin/ClubRegister', [
            'clubs' => $clubs,
            'registeredClubIds' => $registeredClubIds,
            'usersWithoutClubs' => $usersWithoutClubs,
            'currentSchoolYear' => SchoolYear::current(),
        ]);
    }

    public function storeClub(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $club = Club::create($request->all());

        return redirect()->back()->with('success', 'Club created successfully!');
    }

    public function registerClub(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if club is already registered for current school year
        $existingRegistration = ClubRegister::where('club_id', $request->club_id)
            ->where('school_year_id', SchoolYear::current()->id)
            ->first();

        if ($existingRegistration) {
            return redirect()->back()->with('error', 'Club is already registered for this school year.');
        }

        // Check if user already has a club for current school year
        $existingUserRegistration = ClubRegister::where('user_id', $request->user_id)
            ->where('school_year_id', SchoolYear::current()->id)
            ->first();

        if ($existingUserRegistration) {
            return redirect()->back()->with('error', 'User already has a club for this school year.');
        }

        ClubRegister::create([
            'club_id' => $request->club_id,
            'user_id' => $request->user_id,
            'school_year_id' => SchoolYear::current()->id,
        ]);

        return redirect()->back()->with('success', 'Club registered successfully!');
    }
}
