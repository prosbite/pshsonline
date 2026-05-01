<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Support\Facades\Http;
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

    public function updateSchedule()
    {
        try {
            $teachers = Teacher::query()
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get()
                ->map(function (Teacher $teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => trim(collect([
                            $teacher->first_name,
                            $teacher->middle_name,
                            $teacher->last_name,
                            $teacher->suffix,
                        ])->filter()->implode(' ')),
                        'email' => $teacher->email,
                        'phone' => $teacher->phone,
                        'status' => $teacher->status,
                    ];
                })
                ->values();

            $url = 'https://n8n.cidonline.work/webhook-test/mywebhook';

            $response = Http::post($url, [
                'teachers' => $teachers,
                'source' => 'cidonline.work',
                'timestamp' => now()->toDateTimeString(),
                'count' => $teachers->count(),
            ]);

            if ($response->successful()) {
                return redirect()->route('dashboard')->with('success', 'Data sent successfully!');
            }

            return redirect()->route('dashboard')->with('error', 'Failed: ' . $response->status());
        } catch (\Throwable $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to send schedule update.');
        }
    }
}
