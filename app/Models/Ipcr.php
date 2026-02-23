<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ipcr extends Model
{
    protected $fillable = [
        'school_year_id',
        'monitoring',
        'semester',
        'club_type'
    ];
    protected $table = 'ipcr';
}
