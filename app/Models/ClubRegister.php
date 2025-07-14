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
}
