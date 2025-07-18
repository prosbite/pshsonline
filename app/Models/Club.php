<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    /** @use HasFactory<\Database\Factories\ClubFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function adviser()
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    public function clubRegister()
    {
        return $this->hasMany(ClubRegister::class);
    }

    public function currentRegister()
    {
        return $this->hasOne(ClubRegister::class)
                    ->where('school_year_id', SchoolYear::current()->id);
    }

    public function learners()
    {
        return $this->belongsToMany(Learner::class, 'club_learner', 'club_id', 'learner_id')->withTimestamps();
    }
}
