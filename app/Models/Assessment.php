<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = 'assessments';
    protected $fillable = [
        'firstName',
        'lastName',
        'entryCode',
        'assessment',
        'enrollment_id'
    ];
}
