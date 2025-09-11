<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\AccomplishmentReport;

class AccomplishmentReportController extends Controller
{
    public function index()
    {
        $reports = AccomplishmentReport::all();
        return Inertia::render('ClubAccomplishment', [
            'reports' => $reports
        ]);
    }
}
