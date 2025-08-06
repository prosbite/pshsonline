<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubAttendanceLearner extends Model
{
    protected $fillable = [
        'club_attendance_id',
        'learner_id',
        'status',
        'remarks',
    ];
    protected $table = 'club_attendance_learner';
    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }
    public function clubAttendance()
    {
        return $this->belongsTo(ClubAttendance::class);
    }
    public function delinquent()
    {
        return $this->belongsTo(AttendanceDelinquence::class);
    }
}
