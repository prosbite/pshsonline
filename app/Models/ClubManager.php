<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubManager extends Model
{
    protected $fillable = [
        'user_id',
        'club_register_id',
        'school_year_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clubRegister()
    {
        return $this->belongsTo(ClubRegister::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
