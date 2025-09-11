<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quarter extends Model
{
    protected $fillable = [
        'name',
        'status',
        'start_date',
        'end_date',
        'school_year_id',
        'quarter'
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public static function current()
    {
        return self::where('status', 'active')->first();
    }
}
