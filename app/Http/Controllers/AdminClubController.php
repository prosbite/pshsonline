<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubManager;
use App\Models\ClubRegister;
use App\Models\Enrollment;
use App\Models\Learner;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminClubController extends Controller
{
    public function index()
    {
        $gradeLevels = [7, 8, 9];

        $registered_clubs = ClubRegister::query()
            ->with(['club', 'user', 'schoolYear']) // Eager load the main relationships
            ->select(
                'club_registers.*', // Select all columns from club_registers
                // Count learners through the club_register_id pivot for the current registration.
                DB::raw('(
                    SELECT COUNT(cl.learner_id)
                    FROM club_learner cl
                    WHERE cl.club_register_id = club_registers.id
                ) as total_members'),
                DB::raw('(
                    SELECT COUNT(cl.learner_id)
                    FROM club_learner cl
                    JOIN learners l ON cl.learner_id = l.id
                    WHERE cl.club_register_id = club_registers.id
                      AND l.gender = "Female"
                ) as female_members'),
                DB::raw('(
                    SELECT COUNT(cl.learner_id)
                    FROM club_learner cl
                    JOIN learners l ON cl.learner_id = l.id
                    WHERE cl.club_register_id = club_registers.id
                      AND l.gender = "Male"
                ) as male_members')
            )
            ->where('club_registers.school_year_id', SchoolYear::current()->id)
            // Join the clubs table to facilitate ordering by club name
            ->join('clubs', 'club_registers.club_id', '=', 'clubs.id')
            ->orderBy('clubs.name', 'asc') // Order by the club's name
            ->get();
        // dd($registered_clubs);
        $unlisted_learners = Learner::with(['currentEnrollment.section.gradeLevel', 'currentClubRegisters.club'])
            ->whereHas('currentEnrollment.section.gradeLevel', function ($query) use ($gradeLevels) {
                $query->whereIn('grade_level', $gradeLevels);
            })
            ->whereDoesntHave('currentClubRegisters')
            ->get();

        $grade_level_breakdown = Enrollment::with('section.gradeLevel')
            ->whereHas('section.gradeLevel', function ($query) use ($gradeLevels) {
                $query->whereIn('grade_level', $gradeLevels);
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

        $total_students_g7_g9 = $grade_level_breakdown->sum('count');
        $enlisted_students_g7_g9 = $total_students_g7_g9 - $unlisted_learners->count();

        return Inertia::render('admin/Clubs', [
            'registered_clubs' => $registered_clubs,
            'club_student_count' => $enlisted_students_g7_g9,
            'total_students_g7_g9' => $total_students_g7_g9,
            'grade_level_breakdown' => $grade_level_breakdown,
            'unlisted_learners' => $unlisted_learners,
        ]);
    }

    public function managers()
    {
        $clubManagers = ClubManager::with(['user', 'clubRegister.club', 'schoolYear'])
            ->get()
            ->sortBy(function ($manager) {
                $clubName = strtolower($manager->clubRegister?->club?->name ?? '');
                $schoolYearId = str_pad((string) ($manager->school_year_id ?? 0), 4, '0', STR_PAD_LEFT);

                return $clubName.'|'.$schoolYearId;
            })
            ->values();

        return Inertia::render('admin/ClubManagers', [
            'club_managers' => $clubManagers,
        ]);
    }

    public function show(ClubRegister $club)
    {
        $club = $club;
        $registered_clubs = ClubRegister::with(['club'])->where('school_year_id', SchoolYear::current()->id)->get();
        // dd($registered_clubs);
        $currentSchoolYearId = SchoolYear::current()->id;
        $currentSchoolYear = SchoolYear::current();
        $current_manager = ClubManager::with(['user', 'schoolYear'])
            ->where('club_register_id', $club->id)
            ->where('school_year_id', $currentSchoolYearId)
            ->first();
        $previous_manager = ClubManager::with(['user', 'schoolYear', 'clubRegister.club'])
            ->whereHas('clubRegister', function ($query) use ($club) {
                $query->where('club_id', $club->club_id);
            })
            ->where('school_year_id', '<>', $currentSchoolYearId)
            ->orderByDesc('school_year_id')
            ->orderByDesc('id')
            ->first();

        return Inertia::render('admin/ClubDetails', [
            'club' => $club->load(['learners.currentEnrollment.section.gradeLevel', 'user', 'club', 'schoolYear', 'externalinks']),
            'registered_clubs' => $registered_clubs,
            'current_club' => $club->id,
            'current_manager' => $current_manager,
            'previous_manager' => $previous_manager,
            'has_manager' => (bool) $current_manager,
            'current_school_year' => $currentSchoolYear,
        ]);
    }

    public function storeManager(Request $request, ClubRegister $club)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $currentSchoolYear = SchoolYear::current();
        abort_unless($currentSchoolYear, 404);
        $currentClubRegisterId = $club->id;

        $currentManager = ClubManager::where('club_register_id', $currentClubRegisterId)
            ->where('school_year_id', $currentSchoolYear->id)
            ->first();

        if ($currentManager) {
            return redirect()->back()->with('error', 'This club already has a manager for the current school year.');
        }

        $previousManager = ClubManager::whereHas('clubRegister', function ($query) use ($club) {
            $query->where('club_id', $club->club_id);
        })
            ->where('school_year_id', '<>', $currentSchoolYear->id)
            ->orderByDesc('school_year_id')
            ->orderByDesc('id')
            ->first();

        DB::transaction(function () use ($previousManager, $request, $club, $currentSchoolYear, $currentClubRegisterId) {
            if ($previousManager) {
                $user = $previousManager->user;

                if (! $user) {
                    abort(404, 'Previous club manager account could not be found.');
                }

                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'club manager',
                    'status' => 'active',
                ]);

                $renewedManager = ClubManager::where('user_id', $user->id)
                    ->where('school_year_id', $currentSchoolYear->id)
                    ->latest('id')
                    ->first();

                if ($renewedManager) {
                    $renewedManager->update([
                        'club_register_id' => $currentClubRegisterId,
                        'status' => 'active',
                    ]);
                } else {
                    ClubManager::create([
                        'user_id' => $user->id,
                        'club_register_id' => $currentClubRegisterId,
                        'school_year_id' => $currentSchoolYear->id,
                        'status' => 'active',
                    ]);
                }

                return;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'club manager',
                'status' => 'active',
            ]);

            ClubManager::create([
                'user_id' => $user->id,
                'club_register_id' => $currentClubRegisterId,
                'school_year_id' => $currentSchoolYear->id,
                'status' => 'active',
            ]);
        });

        return redirect()->back()->with('success', $previousManager ? 'Club manager renewed successfully.' : 'Club manager added successfully.');
    }

    public function unregisterMember(Request $request)
    {
        $request->validate([
            'learner_id' => 'required|integer',
            'club_reg_id' => 'required|integer|exists:club_registers,id',
        ]);
        $learner = Learner::find($request->learner_id);
        $learner->clubRegisters()->detach($request->club_reg_id);

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
            'user_id' => 'required|exists:users,id',
        ]);

        $club = Club::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        ClubRegister::create([
            'club_id' => $club->id,
            'user_id' => $request->user_id,
            'school_year_id' => SchoolYear::current()->id,
        ]);

        return redirect()->back()->with('success', 'Club created and registered successfully!');
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
