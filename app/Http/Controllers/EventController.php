<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\SchoolYear;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);
        $request['school_year_id'] = SchoolYear::current()->id;
        $event = Event::create($request->all());
        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return redirect()->route('dashboard');
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return redirect()->route('dashboard');
    }
}
