<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubAttendance extends Model
{
    /** @use HasFactory<\Database\Factories\ClubAttendanceFactory> */
    use HasFactory;
    protected $fillable = [
        'club_register_id',
        'school_year_id',
        'activity',
        'date',
        'remarks',
    ];
    public function clubRegister()
    {
        return $this->belongsTo(ClubRegister::class);
    }
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
    public function clubAttendanceLearner()
    {
        return $this->belongsToMany(Learner::class, 'club_attendance_learner', 'club_attendance_id', 'learner_id')
            ->withPivot('status', 'remarks');
    }
    public function singleClubAttendanceLearner()
    {
        return $this->belongsTo(Learner::class, 'club_attendance_learner', 'club_attendance_id', 'learner_id');
    }

    public function delinquents()
    {
        return $this->hasMany(AttendanceDelinquence::class);
    }
    public function delinquentsPivot()
    {
        return $this->belongsToMany(Learner::class, 'club_attendance_learner', 'club_attendance_id', 'learner_id')
            ->withPivot('id', 'status', 'remarks')
            ->wherePivotIn('status', ['unexcused_absence', 'cutting_classes']);
    }
}
