<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubOfficer extends Model
{
    /** @use HasFactory<\Database\Factories\ClubOfficerFactory> */
    use HasFactory;

    protected $fillable = [
        'club_register_id',
        'learner_id',
        'position',
        'order_no',
        'school_year_id',
    ];

    public function clubRegister()
    {
        return $this->belongsTo(ClubRegister::class);
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
