<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /** @use HasFactory<\Database\Factories\FeedbackFactory> */
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'club_register_id',
        'user_id',
        'school_year_id',
        'feedback',
    ];

    protected $casts = [
        'feedback' => 'array',
    ];
}
