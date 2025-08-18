<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'all_day',
        'location',
        'color',
        'created_by',
        'updated_by',
        'school_year_id',
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
