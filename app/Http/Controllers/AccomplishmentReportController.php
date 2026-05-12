<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\AccomplishmentReport;

class AccomplishmentReportController extends Controller
{
    public function index()
    {
        $reports = AccomplishmentReport::with([
            'clubRegister.club',
            'quarter',
            'schoolYear',
        ])->latest()->get();

        return Inertia::render('admin/AccomplishmentReport', [
            'reports' => $reports
        ]);
    }
}
