<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Learner extends Model
{
    protected $appends = [
        'current_club',
    ];

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'gender',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'photo',
        'status',
        'user_id',
    ];
    // protected $with = ['currentEnrollment'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function currentEnrollment()
    {
        return $this->hasOne(Enrollment::class)->where('school_year_id', SchoolYear::current()->id); // or any field to determine "first"
    }

    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_learner', 'learner_id', 'club_id')
            ->withPivot('club_register_id', 'school_year_id', 'status')
            ->withTimestamps();
    }

    public function clubRegisters()
    {
        return $this->belongsToMany(ClubRegister::class, 'club_learner', 'learner_id', 'club_register_id')
            ->withPivot('club_id', 'school_year_id', 'status')
            ->withTimestamps()
            ->orderBy('club_learner.created_at');
    }

    public function currentClubRegisters()
    {
        return $this->belongsToMany(ClubRegister::class, 'club_learner', 'learner_id', 'club_register_id')
            ->withPivot('club_id', 'school_year_id', 'status')
            ->wherePivot('school_year_id', SchoolYear::current()->id)
            ->withTimestamps()
            ->orderBy('club_learner.created_at');
    }

    public function getCurrentClubAttribute()
    {
        $registrations = $this->relationLoaded('currentClubRegisters')
            ? $this->getRelation('currentClubRegisters')
            : $this->currentClubRegisters;

        $clubs = $registrations
            ->map(fn ($registration) => $registration->club)
            ->filter()
            ->values();

        $legacyClubs = $this->clubs()
            ->wherePivot('school_year_id', SchoolYear::current()->id)
            ->wherePivotNull('club_register_id')
            ->get();

        return $clubs
            ->merge($legacyClubs)
            ->unique('id')
            ->values();
    }

    public function learnersWithoutClubs()
    {
        return $this->whereDoesntHave('currentClubRegisters');
    }

    public static function clubEntrants()
    {
        return Enrollment::with(['learner.currentClubRegisters.club', 'section.gradeLevel'])
        ->whereHas('section.gradeLevel', function ($query) {
            $query->whereNotIn('grade_level', [11, 12]);
        })
        ->get();
    }

    public function clubAttendance()
    {
        return $this->belongsToMany(ClubAttendance::class, 'club_attendance_learner', 'learner_id', 'club_attendance_id')
                ->withPivot('status', 'remarks')
                ->withTimestamps()
                ->orderBy('club_attendance_learner.created_at', 'desc');
    }
    public function singleClubAttendance()
    {
        return $this->belongsTo(ClubAttendance::class, 'club_attendance_learner', 'learner_id', 'club_attendance_id');
    }
    public function clubAttendanceLearner()
    {
        return $this->hasMany(ClubAttendanceLearner::class);
    }
}
