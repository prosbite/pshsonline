<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\SchoolYear;

class DashboardController extends Controller
{
    public function index()
    {
        $events = Event::where('school_year_id', SchoolYear::current()->id)->get();
        return Inertia::render('Dashboard', [
            'events' => $events,
        ]);
    }
}
