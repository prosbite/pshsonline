<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubRegister extends Model
{
    /** @use HasFactory<\Database\Factories\ClubRegisterFactory> */
    use HasFactory;

    protected $fillable = [
        'club_id',
        'user_id',
        'school_year_id',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function externalinks()
    {
        return $this->hasMany(Externalink::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function clubAttendances()
    {
        return $this->hasMany(ClubAttendance::class);
    }
    public function clubManagers()
    {
        return $this->hasMany(ClubManager::class);
    }

    public function clubOfficers()
    {
        return $this->hasMany(ClubOfficer::class);
    }

    public function learners()
    {
        return $this->belongsToMany(Learner::class, 'club_learner', 'club_register_id', 'learner_id')
            ->withPivot('club_id', 'school_year_id', 'status')
            ->wherePivot('school_year_id', $this->school_year_id ?? SchoolYear::current()->id)
            ->withTimestamps();
    }
}
