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
    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime', // if you have this field
        'updated_at' => 'datetime', // if you have this field
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
            ->withPivot('id', 'status', 'remarks', 'learner_id','created_at','updated_at');
    }
    public function clubAttendanceLearners()
    {
        return $this->hasMany(ClubAttendanceLearner::class, 'club_attendance_id');
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
