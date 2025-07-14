<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    protected $fillable = [
        'name',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
