<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDelinquence;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ClubAttendance;
use App\Models\ClubRegister;
use App\Models\Learner;
use App\Models\SchoolYear;
use Carbon\Carbon;

class AdminClubAttendanceController extends Controller
{
    private function attendanceDateString($value): ?string
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    private function buildWednesdayDates($startDate, $endDate): array
    {
        $dates = [];
        $cursor = Carbon::parse($startDate)->startOfWeek(Carbon::WEDNESDAY);
        $end = Carbon::parse($endDate)->startOfWeek(Carbon::WEDNESDAY);

        while ($cursor->lte($end)) {
            $dates[] = $cursor->copy();
            $cursor->addWeek();
        }

        return $dates;
    }

    private function findWeeklyAttendance($attendances, int $clubRegisterId, Carbon $wednesday)
    {
        $weekStart = $wednesday->copy()->startOfDay();
        $weekEnd = $wednesday->copy()->addDays(6)->endOfDay();
        $wednesdayString = $wednesday->toDateString();

        $weeklyAttendances = $attendances
            ->filter(function ($attendance) use ($clubRegisterId, $weekStart, $weekEnd) {
                $attendanceDate = Carbon::parse($attendance->date);

                return (int) $attendance->club_register_id === $clubRegisterId
                    && $attendanceDate->betweenIncluded($weekStart, $weekEnd);
            })
            ->sortBy(function ($attendance) {
                return Carbon::parse($attendance->date)->timestamp;
            })
            ->values();

        if ($weeklyAttendances->isEmpty()) {
            return null;
        }

        $exactMatch = $weeklyAttendances->first(function ($attendance) use ($wednesdayString) {
            return Carbon::parse($attendance->date)->toDateString() === $wednesdayString;
        });

        return $exactMatch ?? $weeklyAttendances->first();
    }

    private function formatAuditCell($attendance, Carbon $wednesday): array
    {
        if (! $attendance) {
            return [
                'attendance' => null,
                'label' => '-',
                'is_exact' => false,
                'edit_url' => null,
            ];
        }

        $attendanceDate = Carbon::parse($attendance->date);

        return [
            'attendance' => $attendance,
            'label' => $attendanceDate->format('M j'),
            'is_exact' => $attendanceDate->toDateString() === $wednesday->toDateString(),
            'edit_url' => route('club.attendance.edit', ['attendance_id' => $attendance->id]),
        ];
    }

    public function audit()
    {
        $schoolYear = SchoolYear::current();
        $auditStart = Carbon::create(now()->year, 1, 1)->startOfDay();
        $auditEnd = Carbon::create(now()->year, 4, 22)->endOfDay();

        $clubs = ClubRegister::with('club', 'user')
            ->where('school_year_id', $schoolYear->id)
            ->get()
            ->sortBy(fn ($clubRegister) => $clubRegister->club?->name ?? '')
            ->values();

        $attendances = ClubAttendance::with(['clubRegister.club', 'clubRegister.user'])
            ->where('school_year_id', $schoolYear->id)
            ->whereBetween('date', [$auditStart->toDateString(), $auditEnd->toDateString()])
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($attendances->isEmpty()) {
            return Inertia::render('admin/ClubAttendanceAudit', [
                'attendance' => [],
                'clubs' => $clubs,
                'wednesdays' => [],
            ]);
        }

        $wednesdays = $this->buildWednesdayDates(
            $auditStart,
            $auditEnd
        );

        $attendanceRows = collect($wednesdays)->map(function (Carbon $wednesday) use ($clubs, $attendances) {
            $cells = $clubs->map(function ($clubRegister) use ($attendances, $wednesday) {
                $weeklyAttendance = $this->findWeeklyAttendance($attendances, $clubRegister->id, $wednesday);

                return $this->formatAuditCell($weeklyAttendance, $wednesday);
            })->values();

            return [
                'date' => $wednesday->toDateString(),
                'label' => $wednesday->format('F j, Y'),
                'cells' => $cells,
            ];
        })->values();

        return Inertia::render('admin/ClubAttendanceAudit', [
            'attendance' => $attendanceRows,
            'clubs' => $clubs,
            'wednesdays' => collect($wednesdays)->map(fn (Carbon $date) => $date->toDateString())->values(),
        ]);
    }

    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        // dd($date);
        $clubRegisters = ClubRegister::with('club')->where('school_year_id', SchoolYear::current()->id)->get();
        $attendanceDates = ClubAttendance::select('date')->distinct()->orderBy('date', 'desc')->where('school_year_id', SchoolYear::current()->id)->get()->pluck('date');
        if($attendanceDates->count() > 0 && !$request->date){
            $date = $attendanceDates->first();
        }
        $attendance = ClubAttendance::with(['clubRegister.club', 'clubAttendanceLearner'])->orderBy('date', 'desc')->where('date', $date)->get();
        return Inertia::render('admin/ClubAttendance', [
            'attendance' => $attendance->toArray(),
            'attendanceDates' => $attendanceDates,
            'date' => $date,
            'clubs' => $clubRegisters
        ]);
    }

    public function delinquents(Request $request)
    {
        $date = $request->date ?? Carbon::now()->format('Y-m-d');
        $attendanceDates = ClubAttendance::select('date')->distinct()->orderBy('date', 'desc')->where('school_year_id', SchoolYear::current()->id)->get()->pluck('date');
        if($attendanceDates->count() > 0 && !$request->date){
            $date = $attendanceDates->first();
        }
        // $attendance = ClubAttendance::with(['delinquents.clubAttendanceLearner.learner.currentEnrollment.section.gradeLevel','delinquents.clubAttendanceLearner.clubAttendance.clubRegister.club'])->orderBy('date', 'desc')->get()->where('date', $date);
        $delinquents = AttendanceDelinquence::select('attendance_delinquences.*')
        ->join('club_attendances', 'attendance_delinquences.club_attendance_id', '=', 'club_attendances.id')
        ->where('club_attendances.date', $date)
        ->orderBy('club_attendances.date', 'desc')
        ->with([
            'clubAttendance.clubRegister.club',
            'clubAttendanceLearner.learner.currentEnrollment.section.gradeLevel',
        ])
        ->get();
        return Inertia::render('admin/ClubAttendanceDelinquents', [
            'delinquents' => $delinquents,
            'attendanceDates' => $attendanceDates,
            'date' => $date
        ]);
    }

    public function deleteAttendance(Request $request)
    {
        $attendance = ClubAttendance::find($request->id);
        $attendance->delete();
        return redirect()->back();
    }

    public function infractions()
{
    $infractions = Learner::whereHas('clubAttendance', function ($query) {
        $query->whereIn('status', [
            'excused_absence',
            'unexcused_absence',
            'cutting_classes'
        ])
        ->whereDate('date', '>', '2025-12-07'); // ✅ Add your date condition here
    })
    ->with([
        'clubAttendance' => function ($query) {
            $query->whereIn('status', [
                'excused_absence',
                'unexcused_absence',
                'cutting_classes'
            ])
            ->whereDate('date', '>', '2025-12-07'); // ✅ Also apply it here to filter loaded data
        },
        'clubAttendance.clubRegister.club',
        'currentEnrollment.section.gradeLevel'
    ])
    ->orderBy('last_name', 'asc')
    ->get();

    return Inertia::render('admin/ClubAttendanceInfractions', [
        'infractions' => $infractions,
    ]);
}

    public function accomplishmentSummary()
    {
        $user = auth()->user();
        abort_unless($user && ($user->role === 'admin' || $user->name === 'Chardy C. Fernando'), 403);

        $schoolYear = SchoolYear::current();
        abort_unless($schoolYear, 404);

        $clubs = ClubRegister::with([
            'club',
            'clubAttendances' => function ($query) {
                $query->whereNotNull('image')
                    ->orderBy('date', 'desc')
                    ->orderBy('created_at', 'desc');
            },
        ])
            ->where('school_year_id', $schoolYear->id)
            ->get()
            ->sortBy(fn ($register) => $register->club?->name ?? '')
            ->values();

        return Inertia::render('admin/AccomplishmentSummary', [
            'clubs' => $clubs,
            'schoolYear' => $schoolYear,
        ]);
    }

}
