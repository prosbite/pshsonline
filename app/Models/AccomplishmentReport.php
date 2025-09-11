<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccomplishmentReport extends Model
{
    protected $fillable = [
        'club_register_id',
        'quarter_id',
        'school_year_id',
        'report',
        'remarks',
        'status',
    ];

    public function clubRegister()
    {
        return $this->belongsTo(ClubRegister::class);
    }

    public function quarter()
    {
        return $this->belongsTo(Quarter::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
