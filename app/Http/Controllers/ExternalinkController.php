<?php

namespace App\Http\Controllers;

use App\Models\ClubRegister;
use App\Models\Externalink;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ExternalinkController extends Controller
{
    public function index()
    {
        $currentSchoolYear = SchoolYear::current();

        $externalLinks = Externalink::with(['clubRegister.club', 'schoolYear'])
            ->where('school_year_id', $currentSchoolYear?->id)
            ->orderByDesc('id')
            ->get();

        $externalLinkNames = Externalink::query()
            ->where('school_year_id', $currentSchoolYear?->id)
            ->whereNotNull('name')
            ->distinct()
            ->orderBy('name')
            ->pluck('name')
            ->values();

        $clubRegisters = ClubRegister::with('club')
            ->where('school_year_id', $currentSchoolYear?->id)
            ->get()
            ->sortBy(fn ($clubRegister) => strtolower($clubRegister->club?->name ?? ''))
            ->values();

        return Inertia::render('admin/ExternalLinks', [
            'external_links' => $externalLinks,
            'external_link_names' => $externalLinkNames,
            'club_registers' => $clubRegisters,
            'current_school_year' => $currentSchoolYear,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'club_register_id' => ['required', 'exists:club_registers,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'link' => ['required', 'url', 'max:2048'],
        ]);

        $clubRegister = ClubRegister::findOrFail($request->club_register_id);

        Externalink::create([
            'club_register_id' => $clubRegister->id,
            'school_year_id' => SchoolYear::current()->id,
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return Redirect::back()->with('success', 'External link created successfully.');
    }

    public function update(Request $request, Externalink $externalLink)
    {
        $request->validate([
            'club_register_id' => ['required', 'exists:club_registers,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'link' => ['required', 'url', 'max:2048'],
        ]);

        $externalLink->update([
            'club_register_id' => $request->club_register_id,
            'school_year_id' => SchoolYear::current()->id,
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return Redirect::back()->with('success', 'External link updated successfully.');
    }

    public function destroy(Externalink $externalLink)
    {
        $externalLink->delete();

        return Redirect::back()->with('success', 'External link deleted successfully.');
    }
}
