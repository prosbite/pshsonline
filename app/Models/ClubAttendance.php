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
}
