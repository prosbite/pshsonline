<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\SchoolYear;
use App\Models\ClubManager;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()?->role === 'club manager') {
            $clubManager = ClubManager::with([
                'user',
                'schoolYear',
                'clubRegister.club.learners.currentEnrollment.section.gradeLevel',
                'clubRegister.user',
                'clubRegister.schoolYear',
            ])
                ->where('user_id', auth()->id())
                ->where('school_year_id', SchoolYear::current()->id)
                ->firstOrFail();

            return Inertia::render('ClubManagerDashboard', [
                'club_manager' => $clubManager,
            ]);
        }

        $events = Event::where('school_year_id', SchoolYear::current()->id)->get();
        return Inertia::render('Dashboard', [
            'events' => $events,
        ]);
    }
}
