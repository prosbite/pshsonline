<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = [
        'school_year',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    public static function current()
    {
        return self::where('status', 'active')->first();
    }
}
