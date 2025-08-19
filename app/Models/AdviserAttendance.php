<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdviserAttendance extends Model
{
    protected $fillable = [
        'school_year_id',
        'date',
        'activity',
        'status',
        'remarks',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('id','status', 'remarks')->withTimestamps();
    }
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
