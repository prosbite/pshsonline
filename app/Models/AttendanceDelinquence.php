<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AttendanceDelinquence extends Model
{
    protected $fillable = [
        'club_attendance_id',
        'club_attendance_learner_id',
        'resolved',
        'link',
        'resolved_by',
        'remarks',
    ];

    public function clubAttendanceLearner()
    {
        return $this->belongsTo(ClubAttendanceLearner::class);
    }

    public function clubAttendance()
    {
        return $this->belongsTo(ClubAttendance::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class);
    }

    public static function today($id)
    {
        return self::query()
        ->join('club_attendances', 'attendance_delinquences.club_attendance_id', '=', 'club_attendances.id')
        ->join('club_attendance_learner', 'attendance_delinquences.club_attendance_learner_id', '=', 'club_attendance_learner.id')
        ->join('learners', 'club_attendance_learner.learner_id', '=', 'learners.id')
        ->leftJoin('users', 'attendance_delinquences.resolved_by', '=', 'users.id')
        ->select(
            'club_attendance_learner.id',
            'attendance_delinquences.id',
            'attendance_delinquences.remarks',
            'attendance_delinquences.resolved',
            'learners.last_name as learner_last_name',
            'learners.first_name as learner_first_name',
            'learners.middle_name as learner_middle_name',
            'club_attendances.date as attendance_date',
            'club_attendance_learner.status as attendance_status',
            'users.name as resolved_by_name'
        )
        ->where('attendance_delinquences.club_attendance_id', $id)
        ->get();
    }
}
